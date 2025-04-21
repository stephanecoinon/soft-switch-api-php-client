<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use StephaneCoinon\SoftSwitch\Exceptions\MalformedJson;
use StephaneCoinon\SoftSwitch\HttpClient;
use Tests\TestCase;

class HttpClientTest extends TestCase
{
    /** @test */
    public function it_accepts_json_when_requesting_the_json_format(): void
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}')
        ]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $http = (new HttpClient('', '', ''))->setClient($client);

        $http->getJson('reqtype');

        // Assert that a GET request was made with header 'Accept: application/json'
        $lastRequest = $mock->getLastRequest();
        $this->assertEquals('GET', $lastRequest->getMethod());
        $this->assertEquals('application/json', $lastRequest->getHeaderLine('Accept'));
        // Assert that the request paramaters include 'format=json'
        $query = $lastRequest->getUri()->getQuery();
        parse_str($query, $queryParams);
        $this->assertArrayHasKey('format', $queryParams);
        $this->assertEquals('json', $queryParams['format']);
    }

    /** @test */
    public function decoding_well_formed_json_response_from_a_get_request(): void
    {
        $http = $this->mockHttpClient([
            new Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}')
        ]);

        $this->assertEquals(['foo' => 'bar'], $http->getJson('reqtype'));
    }

    /** @test */
    public function decoding_malformed_json_response_from_a_get_request_throws_an_exception(): void
    {
        $http = $this->mockHttpClient([
            new Response(200, ['Content-Type' => 'application/json'], '{json: bad}')
        ]);

        try {
            $http->getJson('reqtype');
        } catch (MalformedJson $e) {
            $this->pass();

            return;
        }

        $this->fail('Did not throw MalformedJson even though the API returned a malformed JSON response');
    }

    protected function mockHttpClient(array $responses): HttpClient
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return (new HttpClient('', '', ''))->setClient($client);
    }
}
