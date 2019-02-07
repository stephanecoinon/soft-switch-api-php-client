<?php

namespace StephaneCoinon\SoftSwitch;

use StephaneCoinon\SoftSwitch\Models\PeerCount;

class Api extends HttpClient
{
    /**
     * Return the number of peers on each node and the total.
     *
     * @return Object {'total' => int, 'nodes' => [string => int]}
     */
    public function countPeers()
    {
        return PeerCount::createFromJson(
            json_decode($this->get('COUNTPEERS')->getBody(), true)
        );
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
