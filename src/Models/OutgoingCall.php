<?php

namespace StephaneCoinon\SoftSwitch\Models;

use StephaneCoinon\SoftSwitch\Model;

class OutgoingCall extends Model
{
    public function __construct(array $attributes = []) {
        parent::__construct(array_merge([
            'account' => '',
            'source' => '',
            'phone' => '',
            'sourceclid' => '',
        ], $attributes));
    }

    public function fromAccount(string $account): self
    {
        $this->account = $account;

        if (! $this->source) {
            $this->source = 'ACCOUNT';
        }

        return $this;
    }

    public function to(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function callAs(string $callerId): self
    {
        $this->sourceclid = $callerId;

        return $this;
    }
}
