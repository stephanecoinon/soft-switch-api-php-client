<?php

declare(strict_types=1);

namespace StephaneCoinon\SoftSwitch\Requests;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use StephaneCoinon\SoftSwitch\Api;
use StephaneCoinon\SoftSwitch\Requests\Request;

/**
 * Handles requests to the reqtype=INFO endpoint.
 * 
 * Override this class to create a specific request class for each type of info.
 */
abstract class InfoRequest extends Request
{
    /** Type of info */
    protected string $type = '';

    protected string $model = '';

    protected Carbon $start;

    protected Carbon $end;

    public function __construct(Api $api)
    {
        parent::__construct($api);
        $this->start = Carbon::now()->subDays(30);
        $this->end = Carbon::now();
    }

    /**
     * Set the start date to request info from.
     * 
     * @param string|Carbon $since
     * @return static
     * @throws InvalidArgumentException
     */
    public function since($since)
    {
        if ($since instanceof Carbon) {
            $this->start = $since;
        } elseif (is_string($since)) {
            $this->start = Carbon::parse($since);
        } else {
            throw new InvalidArgumentException('Invalid argument type for since method. Expected string or Carbon instance.');
        }

        return $this;
    }

    public function get(): Collection
    {
        $params = [
            'info' => $this->type,
            'start' => $this->start->format('Y-m-d H:i:s'),
            'end' => $this->end->format('Y-m-d H:i:s'),
        ];

        $results = collect($this->api->getJson('INFO', $params));

        if ($this->limit > 0) {
            $results = $results->take($this->limit);
        }

        // Ensure $this->model is a valid class-string
        if (!class_exists($this->model)) {
            throw new InvalidArgumentException("The model class '{$this->model}' does not exist.");
        }

        return $results->mapInto($this->model);
    }
}
