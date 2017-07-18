<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\Dhl24ShippingExportPlugin\Api;

use BitBag\ShippingExportPlugin\Entity\ShippingGatewayInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ShipmentInterface;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 * @author Damian Murawski <damian.murawski@bitbag.pl>
 */
final class DhlWebClient extends \SoapClient
{
    const DATE_FORMAT = 'yyyy-mm-dd';

    /**
     * @var ShippingGatewayInterface
     */
    private $shippingGateway;

    /**
     * @var ShipmentInterface
     */
    private $shipment;

    /**
     * @var OrderInterface
     */
    private $order;

    /**
     * @param ShippingGatewayInterface $shippingGateway
     * @param ShipmentInterface $shipment
     */
    public function __construct(ShippingGatewayInterface $shippingGateway, ShipmentInterface $shipment)
    {
        $this->shippingGateway = $shippingGateway;
        $this->shipment = $shipment;
        $this->order = $shipment->getOrder();
    }

    public function createShipmentCall()
    {
        $requestData = [
            'authData' => $this->getAuthData(),
            'shipment' => [
                'content' => $this->getContent(),
                'shipmentInfo' => $this->getShipmentInfo(),
                'pieceList' => $this->getPieceList(),
                'ship' => $this->getShip(),
            ]
        ];

        /** @var object $this */
        $this->createShipment($requestData);
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
        foreach ($this->order->getItems() as $item) {
            $content .= $item->getProduct()->getName() . ", ";
        }
        $content = rtrim($content, ", ");

        return $content;
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
                'shippingPaymentType' => $this->resolveShippingPaymentType(),
                'billingAccountNumber' => $this->getShippingGatewayConfig('billing_account_number'),
                'paymentType' => $this->getShippingGatewayConfig('payment_type'),
            ],
            'shipmentTime' => [
                'shipmentDate' => $this->resolvePickupDate(),
                'shipmentStartHour' => $this->getShippingGatewayConfig('shipping_start_hour'),
                'shipmentEndHour' => $this->getShippingGatewayConfig('shipping_end_hour'),
            ],
        ];

        if (true === $this->isCashOnDelivery()) {
            $shipmentInfo['specialServices'] = $this->resolveSpecialServices();
        }
    }

    /**
     * @return array
     */
    private function getPieceList()
    {
        return [
            'item' => [
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
        $shippingAddress = $this->order->getShippingAddress();

        return [
            'shipper' => [
                'address' => [
                    'country' => $this->getShippingGatewayConfig('country'),
                    'name' => $this->getShippingGatewayConfig('name'),
                    'postalCode' => $this->getShippingGatewayConfig('postal_code'),
                    'city' => $this->getShippingGatewayConfig('city'),
                    'street' => $this->getShippingGatewayConfig('street'),
                    'houseNumber' => $this->getShippingGatewayConfig('house_number'),
                    'phoneNumber' => $this->getShippingGatewayConfig('phone_number'),
                ]
            ],
            'receiver' => [
                'address' => [
                    'country' => strtoupper($shippingAddress->getCountryCode()),
                    'name' => $shippingAddress->getFullName(),
                    'postalCode' => $shippingAddress->getPostcode(),
                    'city' => $shippingAddress->getCity(),
                    'street' => $shippingAddress->getCity(),
                ]
            ]
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

    /**
     * @return boolean
     */
    private function isCashOnDelivery()
    {
        $codPaymentMethodCode = $this->getShippingGatewayConfig('cod_payment_method_code');
        $payments = $this->order->getPayments();

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

            return $tomorrow->format(self::DATE_FORMAT);
        }

        return $now->format(self::DATE_FORMAT);
    }

    /**
     * @return array
     */
    private function resolveSpecialServices()
    {
        $collectOnDeliveryValue = number_format($this->order->getTotal(), 2, '.', '');

        return [
            ['serviceType' => 'COD', 'serviceValue' => $collectOnDeliveryValue],
            'collectOnDeliveryForm' => $this->getShippingGatewayConfig('collect_on_delivery_form'),
        ];
    }
}