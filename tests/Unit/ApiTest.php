<?php

declare(strict_types=1);

use StephaneCoinon\SoftSwitch\Api;
use StephaneCoinon\SoftSwitch\Models\DialledCall;
use StephaneCoinon\SoftSwitch\Models\OutgoingCall;

it('dials out and gets a successful response', function () {
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

    expect($dialled)->toBeInstanceOf(DialledCall::class)
        ->and($dialled->wasQueued())->toBeTrue()
        ->and($dialled->id)->toEqual('15c5ec7352f0d7')
        ->and($dialled->getOutgoing())->toBe($outgoing);
});

it('dials out and gets a failed response', function () {
    $outgoingCall = new OutgoingCall;
    $api = Mockery::mock(Api::class)->makePartial();
    $api->shouldReceive('getJson')
        ->with('DIAL', $outgoingCall->getApiParameters())
        ->andReturn([
            'Response' => 'Error',
            'ID' => '15c5ec7352f0d7',
        ]);

    $dialled = $api->dial($outgoingCall);

    expect($dialled)->toBeInstanceOf(DialledCall::class)
        ->and($dialled->failed())->toBeTrue();
});
