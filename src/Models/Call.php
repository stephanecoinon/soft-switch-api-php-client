<?php

declare(strict_types=1);

namespace StephaneCoinon\SoftSwitch\Models;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use StephaneCoinon\SoftSwitch\Model;
use StephaneCoinon\SoftSwitch\Requests\AudioCallRecordingRequest;
use StephaneCoinon\SoftSwitch\Requests\CallRequest;

/**
 * @property string $callerName The name of the caller extracted from the caller id
 * @property string $callerNumber The phone number of the caller extracted from the caller id
 * @property string $clid Caller id
 * @property string $dialledNumber
 * @property string|null $disposition The call's disposition (e.g., ANSWERED, etc.)
 * @property string $firstdst The first destination number
 * @property string|null $uniqueid The unique identifier for the call
 * @property string $userfield
 * @property string $wherelanded The destination where the call landed
 */
class Call extends Model
{
    const ANSWERED = 'ANSWERED';

    const INCOMING = '[inbound]';
    const OUTGOING = '[outbound]';
    
    public function __construct(array $rawAttributes = [])
    {
        parent::__construct([
            'id' => (int) ($rawAttributes['ID'] ?? 0),
            'accountcode' => $rawAttributes['accountcode'] ?? null,
            'start' => isset($rawAttributes['start']) ? Carbon::parse($rawAttributes['start']) : null,
            'answer' => isset($rawAttributes['answer']) ? Carbon::parse($rawAttributes['answer']) : null,
            'end' => isset($rawAttributes['end']) ? Carbon::parse($rawAttributes['end']) : null,
            'clid' => $rawAttributes['clid'] ?? '',
            'realsrc' => $rawAttributes['realsrc'] ?? null,
            'firstdst' => $rawAttributes['firstdst'] ?? '',
            'duration' => (int) ($rawAttributes['duration'] ?? 0),
            'billsec' => (int) ($rawAttributes['billsec'] ?? 0),
            'disposition' => $rawAttributes['disposition'] ?? null,
            'cc_cost' => (float) ($rawAttributes['cc_cost'] ?? 0),
            'dcontext' => $rawAttributes['dcontext'] ?? null,
            'dstchannel' => $rawAttributes['dstchannel'] ?? null,
            'userfield' => $rawAttributes['userfield'] ?? '',
            'uniqueid' => $rawAttributes['uniqueid'] ?? null,
            'prevuniqueid' => $rawAttributes['prevuniqueid'] ?? null,
            'lastdst' => $rawAttributes['lastdst'] ?? null,
            'wherelanded' => $rawAttributes['wherelanded'] ?? '',
            'srcCallID' => $rawAttributes['srcCallID'] ?? null,
            'linkedid' => $rawAttributes['linkedid'] ?? null,
            'peeraccount' => $rawAttributes['peeraccount'] ?? null,
            'originateid' => $rawAttributes['originateid'] ?? null,
            'cc_country' => $rawAttributes['cc_country'] ?? null,
            'cc_network' => $rawAttributes['cc_network'] ?? null,
            'pincode' => $rawAttributes['pincode'] ?? null,
        ]);

        $this->setComputedProperties();
    }

    protected function setComputedProperties(): void
    {
        $this->attributes = array_merge($this->attributes, [
            'callerName' => '',
            'callerNumber' => '',
            'dialledNumber' => $this->firstdst,
        ]);

        $this->extractCaller();
    }

    protected function extractCaller(): void
    {
        if (preg_match('/^"([^"]*)" <(\d+|anonymous)>$/', $this->clid, $matches) === 1) {
            $this->callerName = $matches[1] ?? '';
            $this->callerNumber = $matches[2] ?? '';
        }

        // Remove UK international prefix if present
        if (strlen($this->callerNumber) === 12 && Str::startsWith($this->callerNumber, '44')) {
            // Replace 44 by 0
            $this->callerNumber = '0' . substr($this->callerNumber, 2);
        }
    }

    public static function request(): CallRequest
    {
        return app(CallRequest::class);
    }

    public function wasAnswered(): bool
    {
        return $this->disposition === static::ANSWERED;
    }

    public function isIncoming(): bool
    {
        return $this->userfield === static::INCOMING;
    }

    public function isOutgoing(): bool
    {
        return $this->userfield === static::OUTGOING;
    }

    public function getCallDirectionText(bool $shortText = false): string
    {
        return collect([
            static::INCOMING => $shortText ? 'In' : 'Incoming',
            static::OUTGOING => $shortText ? 'Out' : 'Outgoing',
        ])->get($this->userfield, '');
    }

    public function getCallDirectionShortText(): string
    {
        return $this->getCallDirectionText(true);
    }

    public function recording(): ?CallRecording
    {
        return $this->wasAnswered() ? CallRecording::request()->find($this->uniqueid) : null;
    }

    public function audioRecording(): string
    {
        return $this->wasAnswered() ? app(AudioCallRecordingRequest::class)->find($this->uniqueid) : '';
    }
}