<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class CoachChallengePayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly ?string $challengedEventId,
        public readonly string $outcome,
        public readonly int $challengesRemainingAfter,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'challenged_event_id' => $this->challengedEventId,
            'outcome' => $this->outcome,
            'challenges_remaining_after' => $this->challengesRemainingAfter,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'challenged_event_id' => ['nullable', 'string'],
            'outcome' => ['required', 'string', 'in:successful,unsuccessful'],
            'challenges_remaining_after' => ['required', 'integer'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            challengedEventId: $data['challenged_event_id'] ?? null,
            outcome: $data['outcome'],
            challengesRemainingAfter: $data['challenges_remaining_after'],
        );
    }
}
