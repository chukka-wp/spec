<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class PossessionClockExpiryPayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly string $freeThrowTeamId,
        public readonly int $periodClockSecondsRemaining,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'free_throw_team_id' => $this->freeThrowTeamId,
            'period_clock_seconds_remaining' => $this->periodClockSecondsRemaining,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'free_throw_team_id' => ['required', 'string'],
            'period_clock_seconds_remaining' => ['required', 'integer'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            freeThrowTeamId: $data['free_throw_team_id'],
            periodClockSecondsRemaining: $data['period_clock_seconds_remaining'],
        );
    }
}
