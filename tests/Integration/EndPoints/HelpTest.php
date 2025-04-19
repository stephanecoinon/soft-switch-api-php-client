<?php

declare(strict_types=1);

use Tests\TestCase;

uses(TestCase::class);

it('can fetch the help page', function () {
    $response = $this->api->help();

    expect($response)->toContain('reqtype - Request type');
});
