<?php

namespace StephaneCoinon\SoftSwitch;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use StephaneCoinon\SoftSwitch\Models\DialledCall;
use StephaneCoinon\SoftSwitch\Models\OutgoingCall;
use StephaneCoinon\SoftSwitch\Models\PeerCount;

class Api extends HttpClient
{
    protected ResponseInterface $response;

    /**
     * Return the number of peers on each node and the total.
     */
    public function countPeers(): PeerCount
    {
        return PeerCount::createFromJson($this->getJson('COUNTPEERS'));
    }


    /**
     * Get help page.
     */
    public function help(): string
    {
        return (string) $this->get('HELP')->getBody();
    }

    /**
     * Ensure the return type matches the expected interface.
     */
    public function getBody(): StreamInterface
    {
        return $this->response->getBody();
    }

    /**
     * Initiate a call out.
     */
    public function dial(OutgoingCall $call): DialledCall
    {
        return DialledCall::createFromResponse(
            $this->getJson('DIAL', $call->getApiParameters())
        )->withOutgoing($call);
    }
}
