<?php

namespace Tests\Unit\Laravel;

use StephaneCoinon\SoftSwitch\Api;
use StephaneCoinon\SoftSwitch\Laravel\SoftSwitch;
use Tests\TestCase;

class SoftSwitchServiceProviderTest extends TestCase
{
    /** @test */
    public function it_registers_an_api_instance(): void
    {
        $api = app(Api::class);

        $this->assertInstanceOf(Api::class, $api);
    }

    /** @test */
    public function it_registers_softswitch_as_an_alias_of_api_class(): void
    {
        $this->assertSame(app(Api::class), app('softswitch'));
    }

    /** @test */
    public function facade_resolves_to_an_api_instance(): void
    {
        $api = SoftSwitch::getFacadeRoot();

        $this->assertInstanceOf(Api::class, $api);
    }
}
