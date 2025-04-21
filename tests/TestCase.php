<?php

namespace Tests;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
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

        $app['config']->set('soft-switch', [
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

    public function mockApi(array $httpResponses): void
    {
        $mock = new MockHandler($httpResponses);
        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $api = (new Api('', '', ''))->setClient($client);

        app()->instance(Api::class, $api);
    }
}
