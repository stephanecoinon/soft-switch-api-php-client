<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use StephaneCoinon\SoftSwitch\Exceptions\MalformedJson;
use StephaneCoinon\SoftSwitch\HttpClient;

it('decodes well-formed JSON response from a GET request', function () {
    $http = mockHttpClient([
        new Response(200, ['Content-Type' => 'application/json'], '{"foo": "bar"}')
    ]);

    expect($http->getJson('reqtype'))->toEqual(['foo' => 'bar']);
});

it('throws an exception for malformed JSON response from a GET request', function () {
    $http = mockHttpClient([
        new Response(200, ['Content-Type' => 'application/json'], '{json: bad}')
    ]);

    expect(fn () => $http->getJson('reqtype'))->toThrow(MalformedJson::class);
});

function mockHttpClient(array $responses): HttpClient
{
    $mock = new MockHandler($responses);
    $handler = HandlerStack::create($mock);
    $client = new Client(['handler' => $handler]);

    return (new HttpClient('', '', ''))->setClient($client);
}
