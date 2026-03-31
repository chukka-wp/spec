<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects;

use ChukkaWp\ChukkaSpec\Enums\ExclusionType;

final readonly class ActiveExclusion
{
    public function __construct(
        public string $playerId,
        public string $teamId,
        public int $capNumber,
        public int $remainingSeconds,
        public ExclusionType $exclusionType,
        public ?int $substituteEligibleAt = null,
    ) {}

    public function toArray(): array
    {
        return [
            'player_id' => $this->playerId,
            'team_id' => $this->teamId,
            'cap_number' => $this->capNumber,
            'remaining_seconds' => $this->remainingSeconds,
            'exclusion_type' => $this->exclusionType->value,
            'substitute_eligible_at' => $this->substituteEligibleAt,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            playerId: $data['player_id'],
            teamId: $data['team_id'],
            capNumber: $data['cap_number'],
            remainingSeconds: $data['remaining_seconds'],
            exclusionType: ExclusionType::from($data['exclusion_type']),
            substituteEligibleAt: $data['substitute_eligible_at'] ?? null,
        );
    }
}
