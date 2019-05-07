<?php

declare(strict_types=1);

namespace BitBag\SyliusDhl24PlShippingExportPlugin\Api;

interface SoapClientInterface
{
    public function createShipment(array $requestData, string $wsdl);
}
