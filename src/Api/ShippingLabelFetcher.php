<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusDhl24PlShippingExportPlugin\Api;

use Symfony\Component\HttpFoundation\RequestStack;

class ShippingLabelFetcher implements ShippingLabelFetcherInterface
{
    private WebClientInterface $webClient;

    private SoapClientInterface $soapClient;

    private object $response;

    private RequestStack $requestStack;

    public function __construct(
        RequestStack $requestStack,
        WebClientInterface $webClient,
        SoapClientInterface $soapClient,
    ) {
        $this->requestStack = $requestStack;
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
            $this->requestStack->getSession()->getBag('flashes')->add(
                'error',
                sprintf(
                    'DHL24 Web Service for #%s order: %s',
                    $shipment->getOrder()->getNumber(),
                    $exception->getMessage(),
                ),
            );

            return;
        }
    }

    public function getLabelContent(): ?string
    {
        if (!isset($this->response->createShipmentResult)) {
            return '';
        }

        $this->requestStack->getSession()->getBag('flashes')->add('success', 'bitbag.ui.shipment_data_has_been_exported'); // Add success notification

        return base64_decode($this->response->createShipmentResult->label->labelContent);
    }
}
