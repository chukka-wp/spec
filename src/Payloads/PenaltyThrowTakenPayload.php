<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class PenaltyThrowTakenPayload extends Payload
{
    public function __construct(
        public readonly string $shootingTeamId,
        public readonly ?string $shooterPlayerId,
        public readonly ?int $shooterCapNumber,
        public readonly string $outcome,
        public readonly ?int $homeScoreAfter,
        public readonly ?int $awayScoreAfter,
    ) {}

    public function toArray(): array
    {
        return [
            'shooting_team_id' => $this->shootingTeamId,
            'shooter_player_id' => $this->shooterPlayerId,
            'shooter_cap_number' => $this->shooterCapNumber,
            'outcome' => $this->outcome,
            'home_score_after' => $this->homeScoreAfter,
            'away_score_after' => $this->awayScoreAfter,
        ];
    }

    public static function rules(): array
    {
        return [
            'shooting_team_id' => ['required', 'string'],
            'shooter_player_id' => ['nullable', 'string'],
            'shooter_cap_number' => ['nullable', 'integer'],
            'outcome' => ['required', 'string', 'in:goal,miss,saved,rebound_in_play'],
            'home_score_after' => ['nullable', 'integer'],
            'away_score_after' => ['nullable', 'integer'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            shootingTeamId: $data['shooting_team_id'],
            shooterPlayerId: $data['shooter_player_id'] ?? null,
            shooterCapNumber: $data['shooter_cap_number'] ?? null,
            outcome: $data['outcome'],
            homeScoreAfter: $data['home_score_after'] ?? null,
            awayScoreAfter: $data['away_score_after'] ?? null,
        );
    }
}
