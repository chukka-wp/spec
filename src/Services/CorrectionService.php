<?php

namespace ChukkaWp\ChukkaSpec\Services;

use ChukkaWp\ChukkaSpec\Enums\EventType;
use ChukkaWp\ChukkaSpec\Exceptions\InvalidCorrectionException;
use ChukkaWp\ChukkaSpec\Models\Event;
use ChukkaWp\ChukkaSpec\Models\MatchModel;

class CorrectionService
{
    public function __construct(
        private readonly EventDispatcher $dispatcher,
    ) {}

    public function void(
        MatchModel $match,
        string $targetEventId,
        int $period,
        int $periodClockSeconds,
        ?string $reason = null,
        ?string $recordedBy = null,
    ): Event {
        $this->validateTarget($match, $targetEventId);

        return $this->dispatcher->dispatch(
            match: $match,
            type: EventType::Correction,
            period: $period,
            periodClockSeconds: $periodClockSeconds,
            payload: array_filter([
                'corrects_event_id' => $targetEventId,
                'action' => 'void',
                'reason' => $reason,
            ], fn ($v) => $v !== null),
            recordedBy: $recordedBy,
        );
    }

    public function replace(
        MatchModel $match,
        string $targetEventId,
        string $replacementType,
        array $replacementPayload,
        int $period,
        int $periodClockSeconds,
        ?string $reason = null,
        ?string $recordedBy = null,
    ): Event {
        $this->validateTarget($match, $targetEventId);

        return $this->dispatcher->dispatch(
            match: $match,
            type: EventType::Correction,
            period: $period,
            periodClockSeconds: $periodClockSeconds,
            payload: array_filter([
                'corrects_event_id' => $targetEventId,
                'action' => 'replace',
                'replacement_type' => $replacementType,
                'replacement_payload' => $replacementPayload,
                'reason' => $reason,
            ], fn ($v) => $v !== null),
            recordedBy: $recordedBy,
        );
    }

    private function validateTarget(MatchModel $match, string $targetEventId): void
    {
        $target = Event::where('match_id', $match->id)
            ->where('id', $targetEventId)
            ->first();

        if (! $target) {
            throw new InvalidCorrectionException("Target event {$targetEventId} not found in match {$match->id}");
        }

        if ($target->type === EventType::Correction) {
            throw new InvalidCorrectionException('Cannot correct a correction event');
        }
    }
}
