<?php

namespace StephaneCoinon\SoftSwitch\Models;

use StephaneCoinon\SoftSwitch\Model;

class DialledCall extends Model
{
    public static function createFromResponse(array $response): self
    {
        return new static([
            'success' => $response['Response'] == 'Success',
            'id' => $response['ID'],
        ]);
    }

    public function withOutgoing(OutgoingCall $outgoingCall): self
    {
        $this->outgoing = $outgoingCall;

        return $this;
    }

    public function getOutgoing()
    {
        return $this->outgoing;
    }

    public function wasQueued(): bool
    {
        return $this->success;
    }

    public function failed(): bool
    {
        return ! $this->success;
    }
}