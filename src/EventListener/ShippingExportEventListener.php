<?php

/*
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusDhl24PlShippingExportPlugin\EventListener;

use BitBag\SyliusDhl24PlShippingExportPlugin\Api\ShippingLabelFetcherInterface;
use BitBag\SyliusShippingExportPlugin\Entity\ShippingExportInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\Assert\Assert;

final class ShippingExportEventListener
{
    public const DHL_GATEWAY_CODE = 'dhl24_pl';

    /** @var Filesystem */
    private $filesystem;

    /** @var ObjectManager */
    private $shippingExportManager;

    /** @var string */
    private $shippingLabelsPath;

    private ShippingLabelFetcherInterface $shippingLabelFetcher;

    public function __construct(
        Filesystem $filesystem,
        ObjectManager $shippingExportManager,
        string $shippingLabelsPath,
        ShippingLabelFetcherInterface $shippingLabelFetcher
    ) {
        $this->filesystem = $filesystem;
        $this->shippingExportManager = $shippingExportManager;
        $this->shippingLabelsPath = $shippingLabelsPath;
        $this->shippingLabelFetcher = $shippingLabelFetcher;
    }

    public function exportShipment(ResourceControllerEvent $event): void
    {
        /** @var ShippingExportInterface $shippingExport */
        $shippingExport = $event->getSubject();
        Assert::isInstanceOf($shippingExport, ShippingExportInterface::class);

        $shippingGateway = $shippingExport->getShippingGateway();
        Assert::notNull($shippingGateway);

        if (self::DHL_GATEWAY_CODE !== $shippingGateway->getCode()) {
            return;
        }

        $shipment = $shippingExport->getShipment();

        $this->shippingLabelFetcher->createShipment($shippingGateway, $shipment);

        $labelContent = $this->shippingLabelFetcher->getLabelContent();
        if (empty($labelContent)) {
            return;
        }
        $this->saveShippingLabel($shippingExport, $labelContent, 'pdf'); // Save label
        $this->markShipmentAsExported($shippingExport); // Mark shipment as "Exported"
    }

    public function saveShippingLabel(
        ShippingExportInterface $shippingExport,
        string $labelContent,
        string $labelExtension
    ): void {
        $labelPath = $this->shippingLabelsPath
            . '/' . $this->getFilename($shippingExport)
            . '.' . $labelExtension;

        $this->filesystem->dumpFile($labelPath, $labelContent);
        $shippingExport->setLabelPath($labelPath);

        $this->shippingExportManager->persist($shippingExport);
        $this->shippingExportManager->flush();
    }

    private function getFilename(ShippingExportInterface $shippingExport): string
    {
        $shipment = $shippingExport->getShipment();
        Assert::notNull($shipment);

        $order = $shipment->getOrder();
        Assert::notNull($order);

        $orderNumber = $order->getNumber();

        $shipmentId = $shipment->getId();

        return implode(
            '_',
            [
                $shipmentId,
                preg_replace('~[^A-Za-z0-9]~', '', $orderNumber),
            ]
        );
    }

    private function markShipmentAsExported(ShippingExportInterface $shippingExport): void
    {
        $shippingExport->setState(ShippingExportInterface::STATE_EXPORTED);
        $shippingExport->setExportedAt(new \DateTime());

        $this->shippingExportManager->persist($shippingExport);
        $this->shippingExportManager->flush();
    }
}
