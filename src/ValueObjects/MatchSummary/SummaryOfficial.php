<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class SummaryOfficial
{
    public function __construct(
        public string $role,
        public string $name,
        public ?string $userId = null,
        public ?string $teamId = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'role' => $this->role,
            'name' => $this->name,
            'user_id' => $this->userId,
            'team_id' => $this->teamId,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            role: $data['role'],
            name: $data['name'],
            userId: $data['user_id'] ?? null,
            teamId: $data['team_id'] ?? null,
        );
    }
}
