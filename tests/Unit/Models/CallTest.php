<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use GuzzleHttp\Psr7\Response;
use StephaneCoinon\SoftSwitch\Models\Call;
use StephaneCoinon\SoftSwitch\Models\CallRecording;
use StephaneCoinon\SoftSwitch\Requests\CallRequest;
use Tests\TestCase;

class CallTest extends TestCase
{
    /** @test */
    public function it_can_make_an_api_request(): void
    {
        $request = Call::request();

        $this->assertInstanceOf(CallRequest::class, $request);
    }

    /** @test */
    public function it_checks_the_call_disposition(): void
    {
        $answered = new Call(['disposition' => Call::ANSWERED]);

        $this->assertTrue($answered->wasAnswered());
    }

    /** @test */
    public function it_checks_the_call_direction(): void
    {
        $incoming = new Call(['userfield' => Call::INCOMING]);

        $this->assertTrue($incoming->isIncoming());
        $this->assertFalse($incoming->isOutgoing());
        $this->assertEquals('Incoming', $incoming->getCallDirectionText());
        $this->assertEquals('In', $incoming->getCallDirectionShortText());

        $outgoing = new Call(['userfield' => Call::OUTGOING]);

        $this->assertTrue($outgoing->isOutgoing());
        $this->assertFalse($outgoing->isIncoming());
        $this->assertEquals('Outgoing', $outgoing->getCallDirectionText());
        $this->assertEquals('Out', $outgoing->getCallDirectionShortText());
    }

    public function callerIds(): array
    {
        return [
            // Caller id, name, number
            ['"John Doe" <01234567890>', 'John Doe', '01234567890'],
            ['"" <01234567890>', '', '01234567890'],
            ['"" <anonymous>', '', 'anonymous'],
            ['"John" <123>', 'John', '123'], // internal extension
            ['"Denture" <441474320076>', 'Denture', '01474320076'], // UK prefix
        ];
    }

    /**
     * @test
     * @dataProvider callerIds
     */
    public function it_can_extract_the_caller_name_and_number_from_the_caller_id(string $callerId, string $name, string $number): void
    {
        $call = new Call(['clid' => $callerId]);

        $this->assertEquals($name, $call->callerName);
        $this->assertEquals($number, $call->callerNumber);
    }

    /** @test */
    public function it_can_get_the_recording_meta_data_for_an_answered_call(): void
    {
        $call = new Call([
            'uniqueid' => 'id-123',
            'disposition' => Call::ANSWERED,
        ]);
        $this->mockApi([
            new Response(200, [], 'pbx.soft-switch.uk-123456.789|pbx.soft-switch.uk-123456.789|pbx.soft-switch.uk-123456.789.wav|2025-04-10 09: 10: 53|wav|123456')
        ]);

        $recording = $call->recording();

        $this->assertInstanceOf(CallRecording::class, $recording);
    }

    /** @test */
    public function it_can_get_the_audio_recording_for_an_answered_call(): void
    {
        $call = new Call([
            'uniqueid' => 'id-123',
            'disposition' => Call::ANSWERED,
        ]);
        $this->mockApi([
            new Response(200, [], 'wav-content')
        ]);

        $audio = $call->audioRecording();

        $this->assertEquals('wav-content', $audio);
    }
}