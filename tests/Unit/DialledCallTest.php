<?php

declare(strict_types=1);

use StephaneCoinon\SoftSwitch\Models\DialledCall;

it('makes a new instance from a successful API response', function () {
    $dialled = DialledCall::createFromResponse([
        'Response' => 'Success',
        'Message' => 'Originate successfully queued',
        'ID' => '15c5ec7352f0d7',
    ]);

    expect($dialled)->toBeInstanceOf(DialledCall::class)
        ->and($dialled->wasQueued())->toBeTrue()
        ->and($dialled->failed())->toBeFalse()
        ->and($dialled->id)->toEqual('15c5ec7352f0d7');
});

it('makes a new instance from a failed API response', function () {
    $dialled = DialledCall::createFromResponse([
        'Response' => 'Error',
        'ID' => '15c5ec7352f0d7',
    ]);

    expect($dialled)->toBeInstanceOf(DialledCall::class)
        ->and($dialled->failed())->toBeTrue();
});
