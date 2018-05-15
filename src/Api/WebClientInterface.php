<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace BitBag\Dhl24PlShippingExportPlugin\Api;

use BitBag\SyliusShippingExportPlugin\Entity\ShippingGatewayInterface;
use Sylius\Component\Core\Model\ShipmentInterface;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
interface WebClientInterface
{
    /**
     * @param ShippingGatewayInterface $shippingGateway
     *
     * @return mixed
     */
    public function setShippingGateway(ShippingGatewayInterface $shippingGateway);

    /**
     * @param ShipmentInterface $shipment
     *
     * @return mixed
     */
    public function setShipment(ShipmentInterface $shipment);

    /**
     * @return array
     */
    public function getRequestData();
}
