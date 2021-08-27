<?php

/*
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusDhl24PlShippingExportPlugin\Api;


use SoapFault;

final class SoapClient implements SoapClientInterface
{

    /**
     * @throws SoapFault
     */
    public function createShipment(array $requestData, string $wsdl)
    {
        /** @var object $soapClient */
        $soapClient = new \SoapClient($wsdl);

        return $soapClient->createShipment($requestData);
    }
}
