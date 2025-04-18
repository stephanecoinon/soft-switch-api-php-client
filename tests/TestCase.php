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
            'url' => getenv('SOFT_SWITCH_API_URL'),
            'username' => getenv('SOFT_SWITCH_API_USERNAME'),
            'key' => getenv('SOFT_SWITCH_API_KEY'),
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
