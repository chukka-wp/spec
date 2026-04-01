<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class ShotPayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly ?string $playerId,
        public readonly ?int $capNumber,
        public readonly string $outcome,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'player_id' => $this->playerId,
            'cap_number' => $this->capNumber,
            'outcome' => $this->outcome,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'player_id' => ['nullable', 'string'],
            'cap_number' => ['nullable', 'integer'],
            'outcome' => ['required', 'string', 'in:saved,missed,blocked'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            playerId: $data['player_id'] ?? null,
            capNumber: $data['cap_number'] ?? null,
            outcome: $data['outcome'],
        );
    }
}
