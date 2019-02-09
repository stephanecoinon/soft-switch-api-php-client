<?php

namespace Tests\Unit;

use Mockery;
use StephaneCoinon\SoftSwitch\Api;
use StephaneCoinon\SoftSwitch\Models\OutgoingCall;
use Tests\TestCase;
use StephaneCoinon\SoftSwitch\Models\DialledCall;

class ApiTest extends TestCase
{
    /** @test */
    function dialling_out_and_getting_a_successful_response()
    {
        $api = Mockery::mock(Api::class)->makePartial();
        $api->shouldReceive('getJson')
            ->with('DIAL', [
                'account' => $account = '123-COMPANY',
                'source' => 'ACCOUNT',
                'phone' => $phone = '01234567890',
                'sourceclid' => $callerId = '123',
            ])
            ->andReturn([
                'Response' => 'Success',
                'Message' => 'Originate successfully queued',
                'ID' => '15c5ec7352f0d7',
            ]);

        $dialled = $api->dial(
            $outgoing = (new OutgoingCall)
                ->fromAccount($account)
                ->to($phone)
                ->callAs($callerId)
        );

        $this->assertInstanceOf(DialledCall::class, $dialled);
        $this->assertTrue($dialled->wasQueued());
        $this->assertEquals('15c5ec7352f0d7', $dialled->id);
        $this->assertSame($outgoing, $dialled->getOutgoing());
    }

    /** @test */
    function dialling_out_and_getting_a_failed_response()
    {
        $outgoingCall = new OutgoingCall;
        $api = Mockery::mock(Api::class)->makePartial();
        $api->shouldReceive('getJson')
            ->with('DIAL', $outgoingCall->getApiParameters())
            ->andReturn([
                'Response' => 'Error',
                'ID' => '15c5ec7352f0d7',
            ]);

        $dialled = $api->dial($outgoingCall);

        $this->assertInstanceOf(DialledCall::class, $dialled);
        $this->assertTrue($dialled->failed());
        $this->assertEquals('15c5ec7352f0d7', $dialled->id);
        $this->assertSame($outgoingCall, $dialled->getOutgoing());
    }
}
