<?php

namespace Tests\Unit;

use Exception;
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
    function decoding_well_formed_json_response_from_a_get_request()
    {
        $http = $this->mockHttpClient([
            new Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}')
        ]);

        $this->assertEquals(['foo' => 'bar'], $http->getJson('reqtype'));
    }

    /** @test */
    function decoding_malformed_json_response_from_a_get_request_throws_an_exception()
    {
        $http = $this->mockHttpClient([
            new Response(200, ['Content-Type' => 'application/json'], '{json: bad}')
        ]);

        try {
            $response = $http->getJson('reqtype');
        } catch (MalformedJson $e) {
            return $this->pass();
        } catch (Exception $e) {
            return $this->fail('Received '.get_class($e).' exception when MalformedJson was expected');
        }

        $this->fail('Did not throw MalformedJson even though the API returned a malformed JSON response');
    }

    function mockHttpClient(array $responses)
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        return (new HttpClient('', '', ''))->setClient($client);
    }
}
