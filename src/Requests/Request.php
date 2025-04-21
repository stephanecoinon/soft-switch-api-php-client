<?php

declare(strict_types=1);

namespace StephaneCoinon\SoftSwitch\Requests;

use StephaneCoinon\SoftSwitch\Api;

abstract class Request
{
    protected Api $api;

    /** Max number of results to return */
    protected int $limit = 0;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    /**
     * @return static
     */
    public function take(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }
}