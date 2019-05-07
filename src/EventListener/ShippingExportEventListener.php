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
use BitBag\SyliusShippingExportPlugin\Event\ExportShipmentEvent;

final class ShippingExportEventListener
{
    const DHL_GATEWAY_CODE = 'dhl24_pl';
    const BASE_LABEL_EXTENSION = 'pdf';

    /** @var WebClientInterface */
    private $webClient;

    /** @var SoapClientInterface */
    private $soapClient;

    public function __construct(WebClientInterface $webClient, SoapClientInterface $soapClient)
    {
        $this->webClient = $webClient;
        $this->soapClient = $soapClient;
    }

    public function exportShipment(ExportShipmentEvent $exportShipmentEvent): void
    {
        $shippingExport = $exportShipmentEvent->getShippingExport();
        $shippingGateway = $shippingExport->getShippingGateway();

        if ($shippingGateway->getCode() !== self::DHL_GATEWAY_CODE) {
            return;
        }

        $shipment = $shippingExport->getShipment();
        $this->webClient->setShippingGateway($shippingGateway);
        $this->webClient->setShipment($shipment);

        try {
            $requestData = $this->webClient->getRequestData();

            $response = $this->soapClient->createShipment($requestData, $shippingGateway->getConfigValue('wsdl'));
        } catch (\Exception $exception) {
            $exportShipmentEvent->addErrorFlash(sprintf(
                'DHL24 Web Service for #%s order: %s',
                $shipment->getOrder()->getNumber(),
                $exception->getMessage()));

            return;
        }

        $labelContent = base64_decode($response->createShipmentResult->label->labelContent);
        $extension = self::BASE_LABEL_EXTENSION;

        if ('ZBLP' === $response->createShipmentResult->label->labelType) {
            $extension = 'zpl';
        }

        $exportShipmentEvent->saveShippingLabel($labelContent, $extension);
        $exportShipmentEvent->addSuccessFlash();
        $exportShipmentEvent->exportShipment();
    }
}
