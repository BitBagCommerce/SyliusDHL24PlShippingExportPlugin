<?php

declare(strict_types=1);

namespace BitBag\SyliusDhl24PlShippingExportPlugin\Api;

interface ShippingLabelFetcherInterface
{
    public function createShipment($shippingGateway, $shipment): void;

    public function getLabelContent(): ?string;
}
