<?php

namespace Tests;

use Dotenv\Dotenv;
use StephaneCoinon\SoftSwitch\Api;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected Api $api;

    protected function getPackageProviders($app): array
    {
        return [\StephaneCoinon\SoftSwitch\Laravel\SoftSwitchServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/..');
        $dotenv->load();

        $app['config']->set('services.softswitch', [
            'url' => (string) getenv('SOFT_SWITCH_API_URL'),
            'username' => (string) getenv('SOFT_SWITCH_API_USERNAME'),
            'key' => (string) getenv('SOFT_SWITCH_API_KEY'),
        ]);

        $this->api = app('softswitch');
    }

    public function pass(): void
    {
        $this->assertTrue(true);
    }
}
