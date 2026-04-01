<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class SummaryTeam
{
    /**
     * @param array<int, SummaryPlayer> $players
     */
    public function __construct(
        public string $teamId,
        public string $teamName,
        public ?string $clubName,
        public ?string $capColour,
        public array $players,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'team_id' => $this->teamId,
            'team_name' => $this->teamName,
            'club_name' => $this->clubName,
            'cap_colour' => $this->capColour,
            'players' => array_map(fn (SummaryPlayer $p) => $p->toArray(), $this->players),
        ], fn ($value) => $value !== null);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            teamId: $data['team_id'],
            teamName: $data['team_name'],
            clubName: $data['club_name'] ?? null,
            capColour: $data['cap_colour'] ?? null,
            players: array_map(fn (array $p) => SummaryPlayer::fromArray($p), $data['players'] ?? []),
        );
    }
}
