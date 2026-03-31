<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class CornerThrowPayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly string $side,
        public readonly ?string $takenByPlayerId,
        public readonly ?int $takenByCapNumber,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'side' => $this->side,
            'taken_by_player_id' => $this->takenByPlayerId,
            'taken_by_cap_number' => $this->takenByCapNumber,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'side' => ['required', 'string', 'in:left,right'],
            'taken_by_player_id' => ['nullable', 'string'],
            'taken_by_cap_number' => ['nullable', 'integer'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            side: $data['side'],
            takenByPlayerId: $data['taken_by_player_id'] ?? null,
            takenByCapNumber: $data['taken_by_cap_number'] ?? null,
        );
    }
}
