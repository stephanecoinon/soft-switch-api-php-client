<?php

namespace StephaneCoinon\SoftSwitch\Models;

use StephaneCoinon\SoftSwitch\Model;

class PeerCount extends Model
{
    public static function createFromJson(array $json)
    {
        // Get total count
        $total = $json['total'];

        // Cast peers count to int for each node
        unset($json['total']); // only keep nodes in array
        foreach ($json as $key => $value) {
            $json[$key] = (int) $value;
        }

        return new static([
            'total' => $total,
            'nodes' => $json,
        ]);
    }
}