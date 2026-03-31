<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects;

use ChukkaWp\ChukkaSpec\Enums\Possession;

final readonly class ShootoutState
{
    /**
     * @param array<int, ShootoutShot> $shots
     */
    public function __construct(
        public int $homeScore,
        public int $awayScore,
        public int $currentRound,
        public array $shots,
        public Possession $nextShootingTeam,
    ) {}

    public function toArray(): array
    {
        return [
            'home_score' => $this->homeScore,
            'away_score' => $this->awayScore,
            'current_round' => $this->currentRound,
            'shots' => array_map(fn (ShootoutShot $shot) => $shot->toArray(), $this->shots),
            'next_shooting_team' => $this->nextShootingTeam->value,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            homeScore: $data['home_score'],
            awayScore: $data['away_score'],
            currentRound: $data['current_round'],
            shots: array_map(fn (array $shot) => ShootoutShot::fromArray($shot), $data['shots'] ?? []),
            nextShootingTeam: Possession::from($data['next_shooting_team']),
        );
    }
}
