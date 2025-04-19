<?php

namespace Tests\Integration\EndPoints;

use StephaneCoinon\SoftSwitch\Models\PeerCount;
use Tests\TestCase;

class CountPeersTest extends TestCase
{
    /** @test */
    public function peers_count_can_be_fetched(): void
    {
        $response = $this->api->countPeers();

        $this->assertInstanceOf(PeerCount::class, $response);
        $this->assertGreaterThan(0, $response->total);
        $this->assertIsArray($response->nodes);
        $this->assertEquals($response->total, array_sum($response->nodes));
    }
}
