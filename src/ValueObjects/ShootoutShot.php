<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects;

use ChukkaWp\ChukkaSpec\Enums\ShootoutShotOutcome;

final readonly class ShootoutShot
{
    public function __construct(
        public string $teamId,
        public string $playerId,
        public int $capNumber,
        public int $round,
        public ShootoutShotOutcome $outcome,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'player_id' => $this->playerId,
            'cap_number' => $this->capNumber,
            'round' => $this->round,
            'outcome' => $this->outcome->value,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            teamId: $data['team_id'],
            playerId: $data['player_id'],
            capNumber: $data['cap_number'],
            round: $data['round'],
            outcome: ShootoutShotOutcome::from($data['outcome']),
        );
    }
}
