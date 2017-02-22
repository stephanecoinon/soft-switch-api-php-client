<?php

namespace Tests\Integration\EndPoints;

use Tests\TestCase;

class CountPeersTest extends TestCase
{
    /** @test */
    public function peers_count_can_be_fetched()
    {
        $response = $this->api->countPeers();

        $this->assertObjectHasAttribute('total', $response);
        $this->assertEquals($response->total, array_sum($response->nodes));
    }
}
