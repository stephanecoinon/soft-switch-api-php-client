<?php

namespace StephaneCoinon\SoftSwitch\Models;

use StephaneCoinon\SoftSwitch\Model;

/**
 * @property int $total Total number of peers
 * @property array $nodes List of nodes
 */
class PeerCount extends Model
{
    public static function createFromJson(array $json): self
    {
        // Get total count
        $total = $json['total'];

        // Cast peers count to int for each node
        unset($json['total']); // only keep nodes in array
        foreach ($json as $key => $value) {
            $json[$key] = (int) $value;
        }

        return new PeerCount([
            'total' => $total,
            'nodes' => $json,
        ]);
    }
}