<?php

use StephaneCoinon\SoftSwitch\Models\OutgoingCall;
use Tests\TestCase;

class OutgoingCallTest extends TestCase
{
    public function callerIds(): array
    {
        return [
            // expected sourceclid, caller id, caller name
            ['"" <01234567890>', '01234567890'],
            ['"John Doe" <01234567890>', '01234567890', 'John Doe'],
        ];
    }

    /**
     * @test
     * @dataProvider callerIds
     */
    public function it_can_set_the_caller_id_with_an_optional_name(string $sourceclid, string $callerId, string $callerName = ''): void
    {
        $call = new OutgoingCall;

        $call->callAs($callerId, $callerName);

        $this->assertEquals($sourceclid, $call->sourceclid);
    }
}