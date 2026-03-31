<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class TimeoutStartPayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly int $timeoutsRemainingAfter,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'timeouts_remaining_after' => $this->timeoutsRemainingAfter,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'timeouts_remaining_after' => ['required', 'integer'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            timeoutsRemainingAfter: $data['timeouts_remaining_after'],
        );
    }
}
