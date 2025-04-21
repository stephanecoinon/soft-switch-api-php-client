<?php

declare(strict_types=1);

namespace StephaneCoinon\SoftSwitch\Requests;

use StephaneCoinon\SoftSwitch\Models\CallRecording;
use StephaneCoinon\SoftSwitch\Requests\InfoRequest;

class CallRecordingRequest extends InfoRequest
{
    /**
     * Get the metadata associated to the recording for the call.
     * 
     * @throws \InvalidArgumentException
     */
    public function find(?string $uniqueid = null): ?CallRecording  
    {
        if (is_null($uniqueid)) {
            throw new \InvalidArgumentException('Unique ID is required to find a recording.');
        }

        // Even when requesting JSON format and accept JSON format in header, API endpoint still returns a pipe-separated string so we need to parse it manually.
        $response = $this->api->get('INFO', [
            'info' => 'inforecording',
            'id' => $uniqueid,
        ]);
        $stringRecording = $response->getBody()->getContents();

        if ($stringRecording === '') {
            // Call recording not found
            return null;
        }

        $arrayRecording = explode('|', $stringRecording);

        if (count($arrayRecording) !== 6) {
            throw new \InvalidArgumentException('Invalid inforecording response: expected 6 elements, got ' . count($arrayRecording) . ' for uniqueid ' . $uniqueid);
        }

        return (new CallRecording)->setAttributes([
            'uniqueid' => $arrayRecording[1] ?? null,
            'name' => $arrayRecording[2] ?? null,
            'date' => isset($arrayRecording[3]) ? \DateTime::createFromFormat('Y-m-d H:i:s', $arrayRecording[3]) : null,
            'format' => $arrayRecording[4] ?? null,
            'size' => (int) ($arrayRecording[5] ?? 0),
        ]);
    }
}
