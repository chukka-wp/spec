<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class InjuryStoppagePayload extends Payload
{
    public function __construct(
        public readonly ?string $playerId,
        public readonly ?string $teamId,
        public readonly ?int $capNumber,
        public readonly int $maxStoppageSeconds,
        public readonly string $possessionTeamId,
    ) {}

    public function toArray(): array
    {
        return [
            'player_id' => $this->playerId,
            'team_id' => $this->teamId,
            'cap_number' => $this->capNumber,
            'max_stoppage_seconds' => $this->maxStoppageSeconds,
            'possession_team_id' => $this->possessionTeamId,
        ];
    }

    public static function rules(): array
    {
        return [
            'player_id' => ['nullable', 'string'],
            'team_id' => ['nullable', 'string'],
            'cap_number' => ['nullable', 'integer'],
            'max_stoppage_seconds' => ['required', 'integer'],
            'possession_team_id' => ['required', 'string'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            playerId: $data['player_id'] ?? null,
            teamId: $data['team_id'] ?? null,
            capNumber: $data['cap_number'] ?? null,
            maxStoppageSeconds: $data['max_stoppage_seconds'],
            possessionTeamId: $data['possession_team_id'],
        );
    }
}
