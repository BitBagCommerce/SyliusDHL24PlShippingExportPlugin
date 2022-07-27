<?php

declare(strict_types=1);

namespace BitBag\SyliusDhl24PlShippingExportPlugin\Api;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ShippingLabelFetcher implements ShippingLabelFetcherInterface
{
    /** @var WebClientInterface */
    private $webClient;

    /** @var SoapClientInterface */
    private $soapClient;

    /** @var string */
    private $response;

    private FlashBagInterface $flashBag;

    public function __construct(
        FlashBagInterface $flashBag,
        WebClientInterface $webClient,
        SoapClientInterface $soapClient
    )
    {
        $this->flashBag = $flashBag;
        $this->webClient = $webClient;
        $this->soapClient = $soapClient;
    }

    public function createShipment($shippingGateway, $shipment): void
    {
        try {
            $this->webClient->setShippingGateway($shippingGateway);
            $this->webClient->setShipment($shipment);
            $requestData = $this->webClient->getRequestData();

            $this->response = $this->soapClient->createShipment($requestData, $shippingGateway->getConfigValue('wsdl'));
        } catch (\SoapFault $exception) {
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
    }

    public function getLabelContent(): ?string
    {
        if (!isset($this->response->createShipmentResult)) {
            return '';
        }

        $this->flashBag->add('success', 'bitbag.ui.shipment_data_has_been_exported'); // Add success notification

        return base64_decode($this->response->createShipmentResult->label->labelContent);
    }
}
