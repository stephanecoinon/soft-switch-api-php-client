<?php

declare(strict_types=1);

namespace StephaneCoinon\SoftSwitch\Models;

use Illuminate\Support\Carbon;
use StephaneCoinon\SoftSwitch\Model;
use StephaneCoinon\SoftSwitch\Requests\AudioCallRecordingRequest;
use StephaneCoinon\SoftSwitch\Requests\CallRecordingRequest;

/**
 * @property string|null $uniqueid The unique identifier for the call
 */
class CallRecording extends Model
{
    public function __construct(array $rawAttributes = [])
    {
        parent::__construct([
            'id' => (int) ($rawAttributes['re_id'] ?? 0),
            'uniqueid' => $rawAttributes['re_uniqueid'] ?? null,
            'date' => isset($rawAttributes['re_date']) ? Carbon::parse($rawAttributes['re_date']) : null,
            'name' => $rawAttributes['re_name'] ?? '',
            'format' => $rawAttributes['re_format'] ?? '',
            'size' => (int) ($rawAttributes['re_size'] ?? 0),
        ]);
    }

    public static function request(): CallRecordingRequest
    {
        return app(CallRecordingRequest::class);
    }

    public static function find(string $uniqueid): ?self
    {
        return self::request()->find($uniqueid);
    }   

    public function audio(): string
    {
        return app(AudioCallRecordingRequest::class)->find($this->uniqueid);
    }
}