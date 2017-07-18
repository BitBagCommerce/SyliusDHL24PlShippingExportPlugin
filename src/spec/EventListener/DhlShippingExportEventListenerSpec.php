<?php

namespace spec\BitBag\Dhl24ShippingExportPlugin\EventListener;

use BitBag\Dhl24ShippingExportPlugin\EventListener\DhlShippingExportEventListener;
use PhpSpec\ObjectBehavior;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
final class DhlShippingExportEventListenerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DhlShippingExportEventListener::class);
    }
}
