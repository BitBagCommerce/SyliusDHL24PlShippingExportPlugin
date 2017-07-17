<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace spec\BitBag\DhlShippingExportPlugin\Api;

use BitBag\DhlShippingExportPlugin\Api\DhlWebClient;
use BitBag\ShippingExportPlugin\Entity\ShippingGatewayInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\ShipmentInterface;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
final class DhlWebClientSpec extends ObjectBehavior
{
    function let(ShippingGatewayInterface $shippingGateway, ShipmentInterface $shipment)
    {
        $this->beConstructedWith($shippingGateway, $shipment);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DhlWebClient::class);
    }

    function it_extends_soap_client()
    {
        $this->shouldHaveType(\SoapClient::class);
    }

    function it_exports_shipment()
    {

    }
}
