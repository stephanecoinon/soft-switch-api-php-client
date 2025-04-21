<?php

declare(strict_types=1);

namespace StephaneCoinon\SoftSwitch\Requests;

use Illuminate\Support\Collection;
use StephaneCoinon\SoftSwitch\Models\Call;
use StephaneCoinon\SoftSwitch\Requests\InfoRequest;

class CallRequest extends InfoRequest
{
    protected string $type = 'cdrs';

    protected string $model = Call::class;

    protected string $disposition = '';

    protected string $orderBy = '';

    protected bool $orderDescending = false;

    /**
     * Filter for incoming/outgoing calls bases on Call property 'userfield'.
     * 
     * Possible values are:
     * - empty string for no filter
     * - '[inbound]' for incoming calls
     * - '[outbound]' for outgoing calls
     */
    protected string $callDirection = '';

    public function incoming(): self
    {
        $this->callDirection = Call::INCOMING;

        return $this;
    }

    public function outgoing(): self
    {
        $this->callDirection = Call::OUTGOING;

        return $this;
    }

    public function answered(): self
    {
        $this->disposition = Call::ANSWERED;

        return $this;
    }

    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orderBy = $column;
        $this->orderDescending = $direction === 'desc';

        return $this;
    }

    public function orderByDesc(string $column): self
    {
        return $this->orderBy($column, 'desc');
    }

    public function latest(): self
    {
        return $this->orderBy('start', 'desc');
    }

    public function get(): Collection
    {
        // Backup limit because we're going to handle it manually in this method
        $limit = $this->limit;
        $this->limit = 0;

        // Get all calls without limit
        $calls = parent::get();

        // Restore the limit
        $this->limit = $limit;

        // Filter by disposition if set
        if ($this->disposition !== '' && $this->disposition !== '0') {
            $calls = $calls->filter(fn ($call) => $call->disposition === $this->disposition);
        }

        // Filter by bound (outgoing/incoming) if set
        if ($this->callDirection !== '' && $this->callDirection !== '0') {
            $calls = $calls->filter(fn ($call) => $call->userfield === $this->callDirection);
        }

        // Order the calls after applying all the filters
        if ($this->orderBy !== '' && $this->orderBy !== '0') {
            $calls = $calls->sortBy(fn ($call) => $call->getAttribute($this->orderBy), SORT_REGULAR, $this->orderDescending);
        }

        // Apply the limit last
        if ($this->limit > 0) {
            $calls = $calls->take($this->limit);
        }

        return $calls;
    }
}
