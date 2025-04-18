<?php

namespace Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as BaseTestCase;
use StephaneCoinon\SoftSwitch\Api;

class TestCase extends BaseTestCase
{
    /** @var array ['url' => string, 'username' => string, 'key' => string] */
    protected $apiCredentials;


    /** @before */
    public function bootstrap()
    {
        $this->loadApiCredentials();
        $this->instantiateApiClient();
    }


    protected function loadApiCredentials()
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/..');
        $dotenv->load();

        $this->apiCredentials = [
            'url' => $_ENV['SOFT_SWITCH_API_URL'] ?? null,
            'username' => $_ENV['SOFT_SWITCH_API_USERNAME'] ?? null,
            'key' => $_ENV['SOFT_SWITCH_API_KEY'] ?? null,
        ];
    }


    protected function instantiateApiClient()
    {
        $args = array_values($this->apiCredentials);
        $this->api = new Api(...$args);
    }

    function pass()
    {
        $this->assertTrue(true);
    }
}
