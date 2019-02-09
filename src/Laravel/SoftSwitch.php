<?php

namespace StephaneCoinon\SoftSwitch\Laravel;

use Illuminate\Support\Facades\Facade;

class SoftSwitch extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'softswitch';
    }
}