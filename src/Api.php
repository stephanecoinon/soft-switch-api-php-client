<?php

namespace StephaneCoinon\SoftSwitch;

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
}
