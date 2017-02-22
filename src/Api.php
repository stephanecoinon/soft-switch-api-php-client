<?php

namespace StephaneCoinon\SoftSwitch;

class Api extends HttpClient
{
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
