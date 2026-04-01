<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class SummaryPenalty
{
    public function __construct(
        public int $period,
        public int $periodClockSeconds,
        public string $teamId,
        public ?string $playerId,
        public ?int $capNumber,
        public string $outcome,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'period' => $this->period,
            'period_clock_seconds' => $this->periodClockSeconds,
            'team_id' => $this->teamId,
            'player_id' => $this->playerId,
            'cap_number' => $this->capNumber,
            'outcome' => $this->outcome,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            period: $data['period'],
            periodClockSeconds: $data['period_clock_seconds'],
            teamId: $data['team_id'],
            playerId: $data['player_id'] ?? null,
            capNumber: $data['cap_number'] ?? null,
            outcome: $data['outcome'],
        );
    }
}
