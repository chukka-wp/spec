<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class SummaryPeriodScore
{
    public function __construct(
        public int $period,
        public int $homeScore,
        public int $awayScore,
    ) {}

    public function toArray(): array
    {
        return [
            'period' => $this->period,
            'home_score' => $this->homeScore,
            'away_score' => $this->awayScore,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            period: $data['period'],
            homeScore: $data['home_score'],
            awayScore: $data['away_score'],
        );
    }
}
