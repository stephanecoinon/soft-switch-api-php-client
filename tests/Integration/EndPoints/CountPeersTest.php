<?php

declare(strict_types=1);

use StephaneCoinon\SoftSwitch\Models\PeerCount;
use Tests\TestCase;

uses(TestCase::class);

it('can fetch peers count', function () {
    $response = $this->api->countPeers();

    expect($response)->toBeInstanceOf(PeerCount::class)
        ->and($response->total)->toBeGreaterThan(0)
        ->and($response->nodes)->toBeArray()
        ->and($response->total)->toEqual(array_sum($response->nodes));
});
