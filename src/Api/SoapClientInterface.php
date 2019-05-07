<?php

namespace BitBag\SyliusDhl24PlShippingExportPlugin\Api;

interface SoapClientInterface
{
    public function createShipment(array $requestData, $wsdl);
}
