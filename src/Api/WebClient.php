<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\Dhl24PlShippingExportPlugin\Api;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Webmozart\Assert\Assert;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 * @author Damian Murawski <damian.murawski@bitbag.pl>
 */
final class WebClient implements WebClientInterface
{
    const DATE_FORMAT = 'Y-m-d';

    /**
     * @var ShippingGatewayInterface
     */
    private $shippingGateway;

    /**
     * @var ShipmentInterface
     */
    private $shipment;

    /**
     * {@inheritdoc}
     */
    public function setShippingGateway(ShippingGatewayInterface $shippingGateway)
    {
        $this->shippingGateway = $shippingGateway;
    }

    /**
     * {@inheritdoc}
     */
    public function setShipment(ShipmentInterface $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestData()
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

    /**
     * @return OrderInterface|\Sylius\Component\Order\Model\OrderInterface
     */
    private function getOrder()
    {
        return $this->shipment->getOrder();
    }

    /**
     * @return array
     */
    private function getAuthData()
    {
        return [
            'username' => $this->shippingGateway->getConfigValue('login'),
            'password' => $this->shippingGateway->getConfigValue('password'),
        ];
    }

    /**
     * @return string
     */
    private function getContent()
    {
        $content = "";

        /** @var OrderItemInterface $item */
        foreach ($this->getOrder()->getItems() as $item) {

            $mainTaxon = $item->getProduct()->getMainTaxon();

            if ($mainTaxon !== null) {
                if (stristr($content, $mainTaxon->getName()) === false) {
                    $content .= $mainTaxon->getName() . ", ";
                }
            }
        }

        $content = rtrim($content, ", ");

        return substr($content, 0, 30);
    }

    /**
     * @return array
     */
    private function getShipmentInfo()
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

    /**
     * @return array
     */
    private function getPieceList()
    {
        $weight = $this->shipment->getShippingWeight();
        Assert::greaterThan($weight, 0, sprintf("Shipment weight must be greater than %d.", 0));

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

    /**
     * @return array
     */
    private function getShip()
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
            ]
        ];
    }

    /**
     * @param AddressInterface $address
     *
     * @return string
     */
    private function resolveHouseNumber(AddressInterface $address)
    {
        $street = $address->getStreet();
        $streetParts = explode(" ", $street);

        Assert::greaterThan(count($streetParts), 0, sprintf(
            "Street \"%s\" is invalid. The street format must be something like %s, where %d is the house number.",
            $street,
            "\"Opolska 45\"",
            45
        ));

        return end($streetParts);
    }

    /**
     * @return boolean
     */
    private function isCashOnDelivery()
    {
        $codPaymentMethodCode = $this->getShippingGatewayConfig('cod_payment_method_code');
        $payments = $this->getOrder()->getPayments();

        foreach ($payments as $payment) {
            return $payment->getMethod()->getCode() === $codPaymentMethodCode;
        }

        return false;
    }

    /**
     * @return string
     */
    private function resolvePickupDate()
    {
        $now = new \DateTime();
        $breakingHour = $this->getShippingGatewayConfig('pickup_breaking_hour');

        if (null !== $breakingHour && $now->format('H') >= (int)$breakingHour) {
            $tomorrow = $now->modify("+1 day");

            return $this->resolveWeekend($tomorrow)->format(self::DATE_FORMAT);
        }

        return $this->resolveWeekend($now)->format(self::DATE_FORMAT);
    }

    /**
     * @param \DateTime $date
     *
     * @return \DateTime
     */
    private function resolveWeekend(\DateTime $date)
    {
        $dayOfWeek = (int)$date->format('N');

        if ($dayOfWeek === 6) {

            return $date->modify("+2 days");
        }

        if ($dayOfWeek === 7) {

            return $date->modify("+1 day");
        }

        return $date;
    }

    /**
     * @return array
     */
    private function resolveSpecialServices()
    {
        $collectOnDeliveryValue = number_format($this->getOrder()->getTotal(), 2, '.', '');

        return [
            ['serviceType' => 'COD', 'serviceValue' => $collectOnDeliveryValue],
            'collectOnDeliveryForm' => $this->getShippingGatewayConfig('collect_on_delivery_form'),
        ];
    }

    /**
     * @param $config
     *
     * @return string
     */
    private function getShippingGatewayConfig($config)
    {
        return $this->shippingGateway->getConfigValue($config);
    }
}
