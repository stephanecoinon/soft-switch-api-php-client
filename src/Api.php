<?php

namespace StephaneCoinon\SoftSwitch;

class Api extends HttpClient
{
    /**
     * Return the number of peers on each node and the total.
     *
     * @return Object {'total' => int, 'nodes' => [string => int]}
     */
    public function countPeers()
    {
        $json = json_decode($this->get('COUNTPEERS')->getBody(), true);

        // Get total count
        $total = $json['total'];

        // Cast peers count to int for each node
        unset($json['total']); // only keep nodes in array
        foreach ($json as $key => $value) {
            $json[$key] = (int) $value;
        }

        return (Object) [
            'total' => $total,
            'nodes' => $json,
        ];
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
