<?php

namespace StephaneCoinon\SoftSwitch\Models;

use StephaneCoinon\SoftSwitch\Model;

/**
 * @property string $id
 * @property bool $success Indicates if the call was successful
 * @property OutgoingCall|null $outgoing The outgoing call associated with this dialled call
 */
class DialledCall extends Model
{
    public static function createFromResponse(array $response): self
    {
        return new DialledCall([
            'success' => $response['Response'] == 'Success',
            'id' => $response['ID'],
        ]);
    }

    public function withOutgoing(OutgoingCall $outgoingCall): self
    {
        $this->outgoing = $outgoingCall;

        return $this;
    }

    public function getOutgoing(): ?OutgoingCall
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