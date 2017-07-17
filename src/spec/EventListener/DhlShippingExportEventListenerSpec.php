<?php

namespace spec\BitBag\DhlShippingExportPlugin\EventListener;

use BitBag\DhlShippingExportPlugin\EventListener\DhlShippingExportEventListener;
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
