<?php

/*
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusDhl24PlShippingExportPlugin\EventListener;

use BitBag\SyliusDhl24PlShippingExportPlugin\Api\SoapClientInterface;
use BitBag\SyliusDhl24PlShippingExportPlugin\Api\WebClientInterface;
use BitBag\SyliusDhl24PlShippingExportPlugin\EventListener\ShippingExportEventListener;
use BitBag\SyliusShippingExportPlugin\Entity\ShippingExportInterface;
use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use BitBag\SyliusShippingExportPlugin\Event\ExportShipmentEvent;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\ShipmentInterface;

final class ShippingExportEventListenerSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShippingExportEventListener::class);
    }

    function let(WebClientInterface $webClient, SoapClientInterface $soapClient): void
    {
        $this->beConstructedWith($webClient, $soapClient);
    }

    function it_export_shipment(
        ExportShipmentEvent $exportShipmentEvent,
        ShippingExportInterface $shippingExport,
        ShippingGatewayInterface $shippingGateway,
        ShipmentInterface $shipment,
        WebClientInterface $webClient,
        SoapClientInterface $soapClient,
        Order $order
    ): void {
        $webClient->setShippingGateway($shippingGateway);

        $shippingGateway->getCode()->willReturn(ShippingExportEventListener::DHL_GATEWAY_CODE);
        $shippingGateway->getConfigValue('wsdl')->willReturn('wsdl');

        $webClient->getRequestData()->willReturn([]);
        $webClient->setShippingGateway($shippingGateway)->shouldBeCalled();
        $webClient->setShipment($shipment)->shouldBeCalled();

        $shippingExport->getShipment()->willReturn($shipment);

        $exportShipmentEvent->getShippingExport()->willReturn($shippingExport);
        $exportShipmentEvent->addSuccessFlash()->shouldBeCalled();
        $exportShipmentEvent->exportShipment()->shouldBeCalled();
        $exportShipmentEvent->saveShippingLabel('', 'pdf')->shouldBeCalled();
        $shippingExport->getShippingGateway()->willReturn($shippingGateway);

        $order->getNumber()->willReturn(1000);
        $shipment->getOrder()->willReturn($order);

        $soapClient->createShipment([], 'wsdl')->willReturn(
            (object) ['createShipmentResult' => (object) ['label' => (object) [
                        'labelContent' => '',
                        'labelType' => 't',
                    ],
                ],
            ]
        );

        $this->exportShipment($exportShipmentEvent);
    }
}
