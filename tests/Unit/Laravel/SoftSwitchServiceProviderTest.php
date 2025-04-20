<?php

namespace Tests\Unit\Laravel;

use StephaneCoinon\SoftSwitch\Api;
use StephaneCoinon\SoftSwitch\Laravel\SoftSwitch;
use Tests\TestCase;

class SoftSwitchServiceProviderTest extends TestCase
{
    /** @test */
    public function service_provider_registers_an_api_instance(): void
    {
        $api = app('softswitch');

        $this->assertInstanceOf(Api::class, $api);
    }

    /** @test */
    public function facade_resolves_to_an_api_instance(): void
    {
        $api = SoftSwitch::getFacadeRoot();

        $this->assertInstanceOf(Api::class, $api);
    }
}
