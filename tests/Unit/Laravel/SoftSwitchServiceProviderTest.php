<?php

namespace Tests\Unit\Laravel;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase;
use StephaneCoinon\SoftSwitch\Api;
use StephaneCoinon\SoftSwitch\Laravel\SoftSwitch;

class SoftSwitchServiceProviderTest extends TestCase
{
    /** @test */
    function service_provider_registers_an_api_instance()
    {
        $api = app('softswitch');

        $this->assertInstanceOf(Api::class, $api);
    }

    /** @test */
    function facade_resolves_to_an_api_instance()
    {
        $api = SoftSwitch::getFacadeRoot();

        $this->assertInstanceOf(Api::class, $api);
    }

    protected function getPackageProviders($app)
    {
        return [\StephaneCoinon\SoftSwitch\Laravel\SoftSwitchServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $dotenv = new Dotenv(__DIR__.'/../../..');
        $dotenv->load();
        $app['config']->set('services.softswitch', [
            'url' => getenv('SOFT_SWITCH_API_URL'),
            'username' => getenv('SOFT_SWITCH_API_USERNAME'),
            'key' => getenv('SOFT_SWITCH_API_KEY'),
        ]);
    }
}