<?php

namespace StephaneCoinon\SoftSwitch;

use StephaneCoinon\SoftSwitch\Models\DialledCall;
use StephaneCoinon\SoftSwitch\Models\OutgoingCall;
use StephaneCoinon\SoftSwitch\Models\PeerCount;

class Api extends HttpClient
{
    /**
     * Return the number of peers on each node and the total.
     *
     * @return \StephaneCoinon\SoftSwitch\Models\PeerCount
     */
    public function countPeers()
    {
        return PeerCount::createFromJson($this->getJson('COUNTPEERS'));
    }


    /**
     * Get help page.
     *
     * @return string
     */
    public function help()
    {
        return (string) $this->get('HELP')->getBody();
    }

    /**
     * Initiate a call out.
     *
     * @param  \StephaneCoinon\SoftSwitch\Models\OutgoingCall $call
     * @return \StephaneCoinon\SoftSwitch\Models\DialledCall
     */
    public function dial(OutgoingCall $call): DialledCall
    {
        return DialledCall::createFromResponse(
            $this->getJson('DIAL', $call->getApiParameters())
        )->withOutgoing($call);
    }
}
