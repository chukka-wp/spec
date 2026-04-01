<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class SummaryCard
{
    public function __construct(
        public int $period,
        public int $periodClockSeconds,
        public string $teamId,
        public string $colour,
        public string $issuedTo,
        public ?string $playerId = null,
        public ?int $capNumber = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'period' => $this->period,
            'period_clock_seconds' => $this->periodClockSeconds,
            'team_id' => $this->teamId,
            'colour' => $this->colour,
            'issued_to' => $this->issuedTo,
            'player_id' => $this->playerId,
            'cap_number' => $this->capNumber,
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            period: $data['period'],
            periodClockSeconds: $data['period_clock_seconds'],
            teamId: $data['team_id'],
            colour: $data['colour'],
            issuedTo: $data['issued_to'],
            playerId: $data['player_id'] ?? null,
            capNumber: $data['cap_number'] ?? null,
        );
    }
}
