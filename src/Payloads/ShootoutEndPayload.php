<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class ShootoutEndPayload extends Payload
{
    public function __construct(
        public readonly string $winningTeamId,
        public readonly int $homeShootoutScore,
        public readonly int $awayShootoutScore,
        public readonly int $roundsCompleted,
    ) {}

    public function toArray(): array
    {
        return [
            'winning_team_id' => $this->winningTeamId,
            'home_shootout_score' => $this->homeShootoutScore,
            'away_shootout_score' => $this->awayShootoutScore,
            'rounds_completed' => $this->roundsCompleted,
        ];
    }

    public static function rules(): array
    {
        return [
            'winning_team_id' => ['required', 'string'],
            'home_shootout_score' => ['required', 'integer'],
            'away_shootout_score' => ['required', 'integer'],
            'rounds_completed' => ['required', 'integer'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            winningTeamId: $data['winning_team_id'],
            homeShootoutScore: $data['home_shootout_score'],
            awayShootoutScore: $data['away_shootout_score'],
            roundsCompleted: $data['rounds_completed'],
        );
    }
}
