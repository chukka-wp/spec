<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class SummaryTimeout
{
    public function __construct(
        public int $period,
        public int $periodClockSeconds,
        public string $teamId,
    ) {}

    public function toArray(): array
    {
        return [
            'period' => $this->period,
            'period_clock_seconds' => $this->periodClockSeconds,
            'team_id' => $this->teamId,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            period: $data['period'],
            periodClockSeconds: $data['period_clock_seconds'],
            teamId: $data['team_id'],
        );
    }
}
