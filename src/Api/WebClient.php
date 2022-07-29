<?php

/*
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusDhl24PlShippingExportPlugin\Api;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Webmozart\Assert\Assert;

final class WebClient implements WebClientInterface
{
    public const DATE_FORMAT = 'Y-m-d';

    /** @var ShippingGatewayInterface */
    private $shippingGateway;

    /** @var ShipmentInterface */
    private $shipment;

    public function setShippingGateway(ShippingGatewayInterface $shippingGateway): void
    {
        $this->shippingGateway = $shippingGateway;
    }

    public function setShipment(ShipmentInterface $shipment): void
    {
        $this->shipment = $shipment;
    }

    public function getRequestData(): array
    {
        return [
            'authData' => $this->getAuthData(),
            'shipment' => [
                'content' => $this->getContent(),
                'shipmentInfo' => $this->getShipmentInfo(),
                'pieceList' => $this->getPieceList(),
                'ship' => $this->getShip(),
            ],
        ];
    }

    private function getOrder(): OrderInterface
    {
        return $this->shipment->getOrder();
    }

    private function getAuthData(): array
    {
        return [
            'username' => $this->shippingGateway->getConfigValue('login'),
            'password' => $this->shippingGateway->getConfigValue('password'),
        ];
    }

    private function getContent(): string
    {
        $content = '';

        /** @var OrderItemInterface $item */
        foreach ($this->getOrder()->getItems() as $item) {
            $mainTaxon = $item->getProduct()->getMainTaxon();

            if (null !== $mainTaxon) {
                if (false === stristr($content, $mainTaxon->getName())) {
                    $content .= $mainTaxon->getName() . ', ';
                }
            }
        }

        $content = rtrim($content, ', ');

        return substr($content, 0, 30);
    }

    private function getShipmentInfo(): array
    {
        $shipmentInfo = [
            'dropOffType' => $this->getShippingGatewayConfig('drop_off_type'),
            'serviceType' => $this->getShippingGatewayConfig('service_type'),
            'labelType' => $this->getShippingGatewayConfig('label_type'),
            'billing' => [
                'shippingPaymentType' => $this->getShippingGatewayConfig('shipping_payment_type'),
                'billingAccountNumber' => $this->getShippingGatewayConfig('billing_account_number'),
                'paymentType' => $this->getShippingGatewayConfig('payment_type'),
            ],
            'shipmentTime' => [
                'shipmentDate' => $this->resolvePickupDate(),
                'shipmentStartHour' => $this->getShippingGatewayConfig('shipment_start_hour'),
                'shipmentEndHour' => $this->getShippingGatewayConfig('shipment_end_hour'),
            ],
        ];

        if (true === $this->isCashOnDelivery()) {
            $shipmentInfo['specialServices'] = $this->resolveSpecialServices();
        }

        return $shipmentInfo;
    }

    private function getPieceList(): array
    {
        $weight = $this->shipment->getShippingWeight();
        Assert::greaterThan($weight, 0, sprintf('Shipment weight must be greater than %d.', 0));

        return [
            [
                'type' => $this->getShippingGatewayConfig('package_type'),
                'weight' => $this->shipment->getShippingWeight(),
                'width' => $this->getShippingGatewayConfig('package_width'),
                'height' => $this->getShippingGatewayConfig('package_height'),
                'length' => $this->getShippingGatewayConfig('package_length'),
                'quantity' => 1,
            ],
        ];
    }

    private function getShip(): array
    {
        $shippingAddress = $this->getOrder()->getShippingAddress();

        return [
            'shipper' => [
                'address' => [
                    'country' => 'PL',
                    'name' => $this->getShippingGatewayConfig('name'),
                    'postalCode' => str_replace('-', '', $this->getShippingGatewayConfig('postal_code')),
                    'city' => $this->getShippingGatewayConfig('city'),
                    'street' => $this->getShippingGatewayConfig('street'),
                    'houseNumber' => $this->getShippingGatewayConfig('house_number'),
                    'phoneNumber' => $this->getShippingGatewayConfig('phone_number'),
                ],
            ],
            'receiver' => [
                'address' => [
                    'country' => 'PL',
                    'name' => $shippingAddress->getFullName(),
                    'postalCode' => str_replace('-', '', $shippingAddress->getPostcode()),
                    'houseNumber' => $this->resolveHouseNumber($shippingAddress),
                    'city' => $shippingAddress->getCity(),
                    'street' => $shippingAddress->getStreet(),
                    'phoneNumber' => $shippingAddress->getPhoneNumber(),
                ],
            ],
        ];
    }

    private function resolveHouseNumber(AddressInterface $address): string
    {
        $street = $address->getStreet();
        $streetParts = explode(' ', $street);

        Assert::greaterThan(count($streetParts), 0, sprintf(
            'Street "%s" is invalid. The street format must be something like %s, where %d is the house number.',
            $street,
            '"Opolska 45"',
            45
        ));

        return end($streetParts);
    }

    private function isCashOnDelivery(): bool
    {
        $codPaymentMethodCode = $this->getShippingGatewayConfig('cod_payment_method_code');
        $payments = $this->getOrder()->getPayments();

        foreach ($payments as $payment) {
            return $codPaymentMethodCode === $payment->getMethod()->getCode();
        }

        return false;
    }

    private function resolvePickupDate(): string
    {
        $now = new \DateTime();
        $breakingHour = $this->getShippingGatewayConfig('pickup_breaking_hour');

        if (null !== $breakingHour && $now->format('H') >= (int) $breakingHour) {
            $tomorrow = $now->modify('+1 day');

            return $this->resolveWeekend($tomorrow)->format(self::DATE_FORMAT);
        }

        return $this->resolveWeekend($now)->format(self::DATE_FORMAT);
    }

    private function resolveWeekend(\DateTime $date): \DateTime
    {
        $dayOfWeek = (int) $date->format('N');

        if (6 === $dayOfWeek) {
            return $date->modify('+2 days');
        }

        if (7 === $dayOfWeek) {
            return $date->modify('+1 day');
        }

        return $date;
    }

    private function resolveSpecialServices(): array
    {
        $collectOnDeliveryValue = number_format($this->getOrder()->getTotal(), 2, '.', '');

        return [
            ['serviceType' => 'COD', 'serviceValue' => $collectOnDeliveryValue],
            'collectOnDeliveryForm' => $this->getShippingGatewayConfig('collect_on_delivery_form'),
        ];
    }

    private function getShippingGatewayConfig($config)
    {
        return $this->shippingGateway->getConfigValue($config);
    }
}
