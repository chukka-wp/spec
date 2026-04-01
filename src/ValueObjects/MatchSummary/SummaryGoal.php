<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class SummaryGoal
{
    public function __construct(
        public int $period,
        public int $periodClockSeconds,
        public string $teamId,
        public ?string $playerId,
        public ?int $capNumber,
        public ?string $method,
        public int $homeScoreAfter,
        public int $awayScoreAfter,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'period' => $this->period,
            'period_clock_seconds' => $this->periodClockSeconds,
            'team_id' => $this->teamId,
            'player_id' => $this->playerId,
            'cap_number' => $this->capNumber,
            'method' => $this->method,
            'home_score_after' => $this->homeScoreAfter,
            'away_score_after' => $this->awayScoreAfter,
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
            method: $data['method'] ?? null,
            homeScoreAfter: $data['home_score_after'],
            awayScoreAfter: $data['away_score_after'],
        );
    }
}
