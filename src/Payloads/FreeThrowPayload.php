<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class FreeThrowPayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly ?string $reason,
        public readonly ?string $takenByPlayerId,
        public readonly ?int $takenByCapNumber,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'reason' => $this->reason,
            'taken_by_player_id' => $this->takenByPlayerId,
            'taken_by_cap_number' => $this->takenByCapNumber,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'reason' => ['nullable', 'string', 'in:foul,out_of_bounds,clock_expiry'],
            'taken_by_player_id' => ['nullable', 'string'],
            'taken_by_cap_number' => ['nullable', 'integer'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            reason: $data['reason'] ?? null,
            takenByPlayerId: $data['taken_by_player_id'] ?? null,
            takenByCapNumber: $data['taken_by_cap_number'] ?? null,
        );
    }
}
