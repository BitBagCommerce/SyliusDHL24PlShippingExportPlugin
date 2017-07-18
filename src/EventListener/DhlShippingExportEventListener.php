<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\Dhl24ShippingExportPlugin\EventListener;

use BitBag\Dhl24ShippingExportPlugin\Api\DhlWebClient;
use BitBag\ShippingExportPlugin\Event\ExportShipmentEvent;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
final class DhlShippingExportEventListener
{
    const DHL_GATEWAY_CODE = 'dhl';

    /**
     * @param ExportShipmentEvent $event
     */
    public function exportShipment(ExportShipmentEvent $event)
    {
        $shippingExport = $event->getShippingExport();
        $shippingGateway = $shippingExport->getShippingGateway();

        if ($shippingGateway->getCode() !== self::DHL_GATEWAY_CODE) {
            return;
        }

        $dhlClient = new DhlWebClient($shippingGateway, $shippingExport->getShipment());

        try {
            $dhlClient->createShipmentCall();
        } catch (\Exception $exception) {
            $event->addErrorFlash($exception->getMessage());
        }

        $event->addSuccessFlash();
        $event->exportShipment();
    }
}