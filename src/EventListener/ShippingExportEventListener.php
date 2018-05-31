<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\Dhl24PlShippingExportPlugin\EventListener;

use BitBag\Dhl24PlShippingExportPlugin\Api\SoapClientInterface;
use BitBag\Dhl24PlShippingExportPlugin\Api\WebClientInterface;
use BitBag\SyliusShippingExportPlugin\Event\ExportShipmentEvent;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
final class ShippingExportEventListener
{
    const DHL_GATEWAY_CODE = 'dhl24_pl';
    const BASE_LABEL_EXTENSION = 'pdf';

    /**
     * @var WebClientInterface
     */
    private $webClient;

    private $soapClient;

    /**
     * @param WebClientInterface $webClient
     * @param SoapClientInterface $soapClient
     */
    public function __construct(WebClientInterface $webClient, SoapClientInterface $soapClient)
    {
        $this->webClient = $webClient;
        $this->soapClient = $soapClient;
    }

    /**
     * @param ExportShipmentEvent $exportShipmentEvent
     */
    public function exportShipment(ExportShipmentEvent $exportShipmentEvent)
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
                "DHL24 Web Service for #%s order: %s",
                $shipment->getOrder()->getNumber(),
                $exception->getMessage()));

            return;
        }

        $labelContent = base64_decode($response->createShipmentResult->label->labelContent);
        $extension = self::BASE_LABEL_EXTENSION;

        if ($response->createShipmentResult->label->labelType === 'ZBLP') {
            $extension = 'zpl';
        }

        $exportShipmentEvent->saveShippingLabel($labelContent, $extension);
        $exportShipmentEvent->addSuccessFlash();
        $exportShipmentEvent->exportShipment();
    }
}
