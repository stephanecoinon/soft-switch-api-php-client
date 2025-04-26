<?php

namespace StephaneCoinon\SoftSwitch\Models;

use StephaneCoinon\SoftSwitch\Model;

/**
 * @property string $id
 * @property string $message The message returned by the API
 * @property string $responseText The response text returned by the API
 * @property bool $success Indicates if the call was successful
 * @property OutgoingCall|null $outgoing The outgoing call associated with this dialled call
 */
class DialledCall extends Model
{
    public static function createFromResponse(array $response): self
    {
        return new DialledCall([
            'id' => $response['ID'] ?? '',
            'message' => $response['Message'] ?? '',
            'responseText' => $responseText = $response['Response'] ?? '',
            'success' => $responseText === 'Success',
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