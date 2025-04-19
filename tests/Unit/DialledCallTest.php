<?php

namespace Tests\Unit;

use StephaneCoinon\SoftSwitch\Models\DialledCall;
use Tests\TestCase;

class DialledCallTest extends TestCase
{
    /** @test */
    public function making_new_instance_from_successful_api_response(): void
    {
        $dialled = DialledCall::createFromResponse([
            'Response' => 'Success',
            'Message' => 'Originate successfully queued',
            'ID' => '15c5ec7352f0d7',
        ]);

        $this->assertInstanceOf(DialledCall::class, $dialled);
        $this->assertTrue($dialled->wasQueued());
        $this->assertFalse($dialled->failed());
        $this->assertEquals('15c5ec7352f0d7', $dialled->id);
    }

    /** @test */
    public function making_new_instance_from_failed_api_response(): void
    {
        $dialled = DialledCall::createFromResponse([
            'Response' => 'Error',
            'ID' => '15c5ec7352f0d7',
        ]);

        $this->assertInstanceOf(DialledCall::class, $dialled);
        $this->assertFalse($dialled->wasQueued());
        $this->assertTrue($dialled->failed());
        $this->assertEquals('15c5ec7352f0d7', $dialled->id);
    }
}
