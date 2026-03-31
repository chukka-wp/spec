<?php

namespace ChukkaWp\ChukkaSpec\Services;

use ChukkaWp\ChukkaSpec\Enums\EventType;
use ChukkaWp\ChukkaSpec\Models\Event;
use ChukkaWp\ChukkaSpec\Models\MatchModel;
use ChukkaWp\ChukkaSpec\Payloads\PayloadFactory;
use Illuminate\Support\Facades\DB;

class EventDispatcher
{
    public function dispatch(
        MatchModel $match,
        EventType $type,
        int $period,
        int $periodClockSeconds,
        array $payload = [],
        ?string $recordedBy = null,
    ): Event {
        PayloadFactory::make($type, $payload);

        return DB::transaction(function () use ($match, $type, $period, $periodClockSeconds, $payload, $recordedBy) {
            $nextSequence = ($match->events()->lockForUpdate()->max('sequence') ?? 0) + 1;

            $event = new Event([
                'match_id' => $match->id,
                'sequence' => $nextSequence,
                'type' => $type,
                'period' => $period,
                'period_clock_seconds' => $periodClockSeconds,
                'recorded_at' => now(),
                'recorded_by' => $recordedBy,
                'payload' => $payload,
            ]);

            $event->save();

            if ($type->terminatesMatch()) {
                $match->update(['status' => $type === EventType::MatchAbandoned ? 'abandoned' : 'completed']);
            }

            return $event;
        });
    }
}
