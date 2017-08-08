<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace Tests\BitBag\Dhl24PlShippingExportPlugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Tests\BitBag\Dhl24PlShippingExportPlugin\Behat\Mocker\DHLApiMocker;
use Tests\BitBag\ShippingExportPlugin\Behat\Page\Admin\ShippingExport\IndexPageInterface;

/**
 * @author Patryk Drapik <patryk.drapik@bitbag.pl>
 */
final class ShippingExportContext implements Context
{
    /**
     * @var IndexPageInterface
     */
    private $indexPage;

    /**
     * @var DHLApiMocker
     */
    private $DHLApiMocker;

    /**
     * @param IndexPageInterface $indexPage
     * @param DHLApiMocker $DHLApiMocker
     */
    public function __construct(
        IndexPageInterface $indexPage,
        DHLApiMocker $DHLApiMocker
    )
    {
        $this->DHLApiMocker = $DHLApiMocker;
        $this->indexPage = $indexPage;
    }

    /**
     * @When I export all new shipments to dhl api
     */
    public function iExportAllNewShipments()
    {
        $this->DHLApiMocker->performActionInApiSuccessfulScope(function () {
            $this->indexPage->exportAllShipments();
        });
    }

    /**
     * @When I export first shipment to dhl api
     */
    public function iExportFirsShipments()
    {
        $this->DHLApiMocker->performActionInApiSuccessfulScope(function () {
            $this->indexPage->exportFirsShipment();
        });
    }
}
