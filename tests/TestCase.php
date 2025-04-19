<?php

namespace Tests;

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase as BaseTestCase;
use StephaneCoinon\SoftSwitch\Api;

class TestCase extends BaseTestCase
{
    /**
     * @var array{
     *     url: string,
     *     username: string,
     *     key: string
     * }
     */
    protected array $apiCredentials;

    protected Api $api;

    /** @before */
    public function bootstrap(): void
    {
        $this->loadApiCredentials();
        $this->instantiateApiClient();
    }

    protected function loadApiCredentials(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__.'/..');
        $dotenv->load();

        $this->apiCredentials = [
            'url' => (string) getenv('SOFT_SWITCH_API_URL'),
            'username' => (string) getenv('SOFT_SWITCH_API_USERNAME'),
            'key' => (string) getenv('SOFT_SWITCH_API_KEY'),
        ];
    }

    protected function instantiateApiClient(): void
    {
        $args = array_values($this->apiCredentials);
        $this->api = new Api(...$args);
    }

    public function pass(): void
    {
        $this->assertTrue(true);
    }
}
