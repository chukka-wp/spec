<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\EventType;
use Illuminate\Support\Collection;

class CorrectionPreFilter
{
    /**
     * @return array{
     *     events: Collection,
     *     voided_ids: array<string>
     * }
     */
    public function filter(Collection $events): array
    {
        $corrections = [];
        $voidedIds = [];

        foreach ($events as $event) {
            if ($event->type !== EventType::Correction) {
                continue;
            }

            $payload = $event->payload ?? [];
            $targetId = $payload['corrects_event_id'] ?? null;

            if (! $targetId) {
                continue;
            }

            $action = $payload['action'] ?? 'void';

            if ($action === 'void') {
                $voidedIds[$targetId] = true;
                unset($corrections[$targetId]);
            }

            if ($action === 'replace') {
                unset($voidedIds[$targetId]);
                $corrections[$targetId] = [
                    'type' => $payload['replacement_type'] ?? null,
                    'payload' => $payload['replacement_payload'] ?? [],
                ];
            }
        }

        $filtered = $events->filter(function ($event) use ($voidedIds, $corrections) {
            if ($event->type === EventType::Correction) {
                return false;
            }

            if (isset($voidedIds[$event->id])) {
                return false;
            }

            return true;
        })->map(function ($event) use ($corrections) {
            if (! isset($corrections[$event->id])) {
                return $event;
            }

            $replacement = $corrections[$event->id];
            $clone = clone $event;

            if ($replacement['type']) {
                $clone->type = EventType::from($replacement['type']);
            }

            $clone->payload = $replacement['payload'];

            return $clone;
        })->values();

        return [
            'events' => $filtered,
            'voided_ids' => array_keys($voidedIds),
        ];
    }
}
