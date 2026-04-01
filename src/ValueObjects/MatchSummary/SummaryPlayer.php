<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class SummaryPlayer
{
    public function __construct(
        public int $capNumber,
        public string $name,
        public string $role,
        public bool $isStarting,
        public int $goals,
        public int $personalFouls,
        public int $exclusions,
        public bool $fouledOut,
        public ?string $playerId = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'cap_number' => $this->capNumber,
            'name' => $this->name,
            'role' => $this->role,
            'is_starting' => $this->isStarting,
            'goals' => $this->goals,
            'personal_fouls' => $this->personalFouls,
            'exclusions' => $this->exclusions,
            'fouled_out' => $this->fouledOut,
            'player_id' => $this->playerId,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            capNumber: $data['cap_number'],
            name: $data['name'],
            role: $data['role'],
            isStarting: $data['is_starting'],
            goals: $data['goals'],
            personalFouls: $data['personal_fouls'],
            exclusions: $data['exclusions'],
            fouledOut: $data['fouled_out'],
            playerId: $data['player_id'] ?? null,
        );
    }
}
