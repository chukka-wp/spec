<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class GoalPayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly ?string $playerId,
        public readonly ?int $capNumber,
        public readonly ?string $method,
        public readonly int $homeScoreAfter,
        public readonly int $awayScoreAfter,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'player_id' => $this->playerId,
            'cap_number' => $this->capNumber,
            'method' => $this->method,
            'home_score_after' => $this->homeScoreAfter,
            'away_score_after' => $this->awayScoreAfter,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'player_id' => ['nullable', 'string'],
            'cap_number' => ['nullable', 'integer'],
            'method' => ['nullable', 'string', 'in:field_goal,penalty_throw,own_goal'],
            'home_score_after' => ['required', 'integer'],
            'away_score_after' => ['required', 'integer'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            playerId: $data['player_id'] ?? null,
            capNumber: $data['cap_number'] ?? null,
            method: $data['method'] ?? null,
            homeScoreAfter: $data['home_score_after'],
            awayScoreAfter: $data['away_score_after'],
        );
    }
}
