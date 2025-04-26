<?php

namespace StephaneCoinon\SoftSwitch\Models;

use StephaneCoinon\SoftSwitch\Model;

/**
 * @property string $account
 * @property string $source
 * @property string $phone
 * @property string $sourceclid
 */
class OutgoingCall extends Model
{
    public function __construct(array $attributes = [])
    {
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

        if ($this->source === '' || $this->source === '0') {
            $this->source = 'ACCOUNT';
        }

        return $this;
    }

    public function to(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function callAs(string $callerId, string $callerName = ''): self
    {
        $this->sourceclid = sprintf('"%s" <%s>', $callerName, $callerId);

        return $this;
    }
}
