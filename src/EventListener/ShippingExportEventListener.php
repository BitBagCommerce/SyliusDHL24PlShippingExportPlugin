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

use BitBag\SyliusDhl24PlShippingExportPlugin\Api\SoapClientInterface;
use BitBag\SyliusDhl24PlShippingExportPlugin\Api\WebClientInterface;
use BitBag\SyliusShippingExportPlugin\Entity\ShippingExportInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Webmozart\Assert\Assert;

final class ShippingExportEventListener
{
    /** @var FlashBagInterface */
    private $flashBag;

    /** @var Filesystem */
    private $filesystem;

    /** @var ObjectManager */
    private $shippingExportManager;

    /** @var string */
    private $shippingLabelsPath;

    /** @var WebClientInterface */
    private $webClient;

    /** @var SoapClientInterface */
    private $soapClient;

    public function __construct(
        FlashBagInterface $flashBag,
        Filesystem $filesystem,
        ObjectManager $shippingExportManager,
        string $shippingLabelsPath,
        WebClientInterface $webClient,
        SoapClientInterface $soapClient
    ) {
        $this->flashBag = $flashBag;
        $this->filesystem = $filesystem;
        $this->shippingExportManager = $shippingExportManager;
        $this->shippingLabelsPath = $shippingLabelsPath;
        $this->webClient = $webClient;
        $this->soapClient = $soapClient;
    }

    public function exportShipment(ResourceControllerEvent $event): void
    {
        /** @var ShippingExportInterface $shippingExport */
        $shippingExport = $event->getSubject();
        Assert::isInstanceOf($shippingExport, ShippingExportInterface::class);

        $shippingGateway = $shippingExport->getShippingGateway();
        Assert::notNull($shippingGateway);

        if ('dhl24_pl' !== $shippingGateway->getCode()) {
            return;
        }

        $shipment = $shippingExport->getShipment();

        $this->webClient->setShippingGateway($shippingGateway);
        $this->webClient->setShipment($shipment);

        try {
            $requestData = $this->webClient->getRequestData();

            $response = $this->soapClient->createShipment($requestData, $shippingGateway->getConfigValue('wsdl'));
        } catch (\Exception $exception) {
            $this->flashBag->add(
                'error',
                sprintf(
                    'DHL24 Web Service for #%s order: %s',
                    $shipment->getOrder()->getNumber(),
                    $exception->getMessage()
                )
            );

            return;
        }

        $labelContent = base64_decode($response->createShipmentResult->label->labelContent);

        $this->flashBag->add('success', 'bitbag.ui.shipment_data_has_been_exported'); // Add success notification
        $this->saveShippingLabel($shippingExport, $labelContent, 'pdf'); // Save label
        //$this->markShipmentAsExported($shippingExport); // Mark shipment as "Exported"
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
