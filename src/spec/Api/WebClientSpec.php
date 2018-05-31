<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace spec\BitBag\Dhl24PlShippingExportPlugin\Api;

use BitBag\Dhl24PlShippingExportPlugin\Api\WebClient;
use BitBag\Dhl24PlShippingExportPlugin\Api\WebClientInterface;
use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethod;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Model\TaxonInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
final class WebClientSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(WebClient::class);
        $this->shouldHaveType(WebClientInterface::class);
    }

    function it_create_request_data_shipment
    (
        ShippingGatewayInterface $shippingGateway,
        OrderInterface $order,
        ShipmentInterface $shipment,
        OrderItemInterface $orderItem,
        ProductInterface $product,
        PaymentInterface $payment,
        PaymentMethod $paymentMethod,
        AddressInterface $address,
        TaxonInterface $taxon
    )
    {
        $shippingGateway->getConfigValue('wsdl')->willReturn('https://sandbox.dhl24.com.pl/webapi2');
        $shippingGateway->getConfigValue('login')->willReturn('123');
        $shippingGateway->getConfigValue('password')->willReturn('123');
        $shippingGateway->getConfigValue('drop_off_type')->willReturn('REQUEST_COURIER');
        $shippingGateway->getConfigValue('service_type')->willReturn('AH');
        $shippingGateway->getConfigValue('label_type')->willReturn('LP');
        $shippingGateway->getConfigValue('shipping_payment_type')->willReturn('SHIPPER');
        $shippingGateway->getConfigValue('billing_account_number')->willReturn('1204663');
        $shippingGateway->getConfigValue('payment_type')->willReturn('BANK_TRANSFER');
        $shippingGateway->getConfigValue('shipment_start_hour')->willReturn('12:00');
        $shippingGateway->getConfigValue('shipment_end_hour')->willReturn('15:00');
        $shippingGateway->getConfigValue('pickup_breaking_hour')->willReturn('15:00');
        $shippingGateway->getConfigValue('cod_payment_method_code')->willReturn('stripe_checkout');
        $shippingGateway->getConfigValue('collect_on_delivery_form')->willReturn('BANK_TRANSFER');
        $shippingGateway->getConfigValue('package_type')->willReturn('PACKAGE');
        $shippingGateway->getConfigValue('package_width')->willReturn(60);
        $shippingGateway->getConfigValue('package_height')->willReturn(40);
        $shippingGateway->getConfigValue('package_length')->willReturn(40);
        $shippingGateway->getConfigValue('name')->willReturn('Janek');
        $shippingGateway->getConfigValue('postal_code')->willReturn('00-909');
        $shippingGateway->getConfigValue('city')->willReturn('Wawa');
        $shippingGateway->getConfigValue('street')->willReturn('Leśna');
        $shippingGateway->getConfigValue('house_number')->willReturn(9);
        $shippingGateway->getConfigValue('phone_number')->willReturn(123456789);

        $taxon->getName()->willReturn('test');

        $product->getMainTaxon()->willReturn($taxon);

        $orderItem->getProduct()->willReturn($product);

        $paymentMethod->getCode()->willReturn('stripe_checkout');
        $payment->getMethod()->willReturn($paymentMethod);

        $address->getCountryCode()->willReturn('PL');
        $address->getFullName()->willReturn('Janek');
        $address->getPostcode()->willReturn(22222);
        $address->getStreet()->willReturn('Leśna 9');
        $address->getCity()->willReturn('Wawa');
        $address->getPhoneNumber()->willReturn(123456789);

        $order->getShippingAddress()->willReturn($address);
        $order->getItems()->willReturn(new ArrayCollection([$orderItem->getWrappedObject()]));
        $order->getPayments()->willReturn(new ArrayCollection([$payment->getWrappedObject()]));
        $order->getTotal()->willReturn(77000);

        $shipment->getOrder()->willReturn($order);
        $shipment->getShippingWeight()->willReturn(20);

        $this->setShippingGateway($shippingGateway);
        $this->setShipment($shipment);

        $this->getRequestData();
    }
}
