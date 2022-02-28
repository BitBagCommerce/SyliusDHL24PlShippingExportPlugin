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

use BitBag\SyliusDhl24PlShippingExportPlugin\Api\ShippingLabelFetcherInterface;
use BitBag\SyliusDhl24PlShippingExportPlugin\EventListener\ShippingExportEventListener;
use BitBag\SyliusShippingExportPlugin\Entity\ShippingExportInterface;
use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use BitBag\SyliusShippingExportPlugin\Repository\ShippingExportRepository;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ShipmentInterface;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\Assert\InvalidArgumentException;

final class ShippingExportEventListenerSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ShippingExportEventListener::class);
    }

    function let(
        Filesystem $filesystem,
        ShippingExportRepository $shippingExportRepository,
        ShippingLabelFetcherInterface $shippingLabelFetcher
    ): void
    {
        $this->beConstructedWith(
            $filesystem,
            $shippingExportRepository,
            'shippingLabel',
             $shippingLabelFetcher
        );
    }

    function it_export_shipment(
        ResourceControllerEvent $event,
        ShippingExportInterface $shippingExport,
        ShippingGatewayInterface $shippingGateway,
        ShipmentInterface $shipment,
        ShippingLabelFetcherInterface $shippingLabelFetcher,
        ShippingExportEventListener $shippingExportEventListener
    ): void {
        $labelContent = 'labelContent';
        $shippingGatewayCode = "'dhl24_pl'";

        $event->getSubject()
            ->willReturn($shippingExport);

        $shippingExport->getShippingGateway()
            ->willReturn($shippingGateway);

        $shippingGateway->getCode()
            ->willReturn($shippingGatewayCode);

        $shippingExport->getShipment()
            ->willReturn($shipment);

        $shippingLabelFetcher->getLabelContent()
            ->willReturn($labelContent);

        $this->exportShipment($event);
    }

    function it_throws_exception_if_given_wrong_instance(
        ResourceControllerEvent $event,
        ShippingGatewayInterface $shippingGateway
    ): void {
        $event->getSubject()
            ->willReturn($shippingGateway);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('exportShipment', [$event]);
    }

    function it_throws_exception_if_shipping_gateway_is_null(
        ResourceControllerEvent $event,
        ShippingExportInterface $shippingExport
    ): void {
        $shippingGateway = null;
        $event->getSubject()
            ->willReturn($shippingExport);
        
        $shippingExport->getShippingGateway()
            ->willReturn($shippingGateway);

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('exportShipment', [$event]);
    }
}
