<?php

declare(strict_types=1);

namespace StephaneCoinon\SoftSwitch\Requests;

use StephaneCoinon\SoftSwitch\Requests\InfoRequest;

class AudioCallRecordingRequest extends InfoRequest
{
    public function find(?string $uniqueid = null): string
    {
        if (is_null($uniqueid)) {
            throw new \InvalidArgumentException('Unique ID is required to find a recording.');
        }

        $response = $this->api->get('INFO', [
            'info' => 'recording',
            'id' => $uniqueid,
        ]);

        return $response->getBody()->getContents();
    }
}
