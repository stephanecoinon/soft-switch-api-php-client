<?php

namespace Tests\Integration\EndPoints;

use Tests\TestCase;

class HelpTest extends TestCase
{
    /** @test */
    public function help_page_can_be_fetched()
    {
        $response = $this->api->help();

        $this->assertContains('reqtype - Request type', $response);
    }
}
