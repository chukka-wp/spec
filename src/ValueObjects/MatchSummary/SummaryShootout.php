<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class SummaryShootout
{
    /**
     * @param array<int, SummaryShootoutShot> $shots
     */
    public function __construct(
        public int $homeScore,
        public int $awayScore,
        public array $shots,
    ) {}

    public function toArray(): array
    {
        return [
            'home_score' => $this->homeScore,
            'away_score' => $this->awayScore,
            'shots' => array_map(fn (SummaryShootoutShot $s) => $s->toArray(), $this->shots),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            homeScore: $data['home_score'],
            awayScore: $data['away_score'],
            shots: array_map(fn (array $s) => SummaryShootoutShot::fromArray($s), $data['shots'] ?? []),
        );
    }
}
