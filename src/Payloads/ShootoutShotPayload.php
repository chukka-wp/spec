<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class ShootoutShotPayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly string $playerId,
        public readonly int $capNumber,
        public readonly int $round,
        public readonly string $outcome,
        public readonly int $homeShootoutScoreAfter,
        public readonly int $awayShootoutScoreAfter,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'player_id' => $this->playerId,
            'cap_number' => $this->capNumber,
            'round' => $this->round,
            'outcome' => $this->outcome,
            'home_shootout_score_after' => $this->homeShootoutScoreAfter,
            'away_shootout_score_after' => $this->awayShootoutScoreAfter,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'player_id' => ['required', 'string'],
            'cap_number' => ['required', 'integer'],
            'round' => ['required', 'integer'],
            'outcome' => ['required', 'string', 'in:goal,miss,saved'],
            'home_shootout_score_after' => ['required', 'integer'],
            'away_shootout_score_after' => ['required', 'integer'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            playerId: $data['player_id'],
            capNumber: $data['cap_number'],
            round: $data['round'],
            outcome: $data['outcome'],
            homeShootoutScoreAfter: $data['home_shootout_score_after'],
            awayShootoutScoreAfter: $data['away_shootout_score_after'],
        );
    }
}
