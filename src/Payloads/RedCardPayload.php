<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class RedCardPayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly string $issuedTo,
        public readonly ?string $playerId,
        public readonly ?int $capNumber,
        public readonly bool $precededByYellowCard,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'issued_to' => $this->issuedTo,
            'player_id' => $this->playerId,
            'cap_number' => $this->capNumber,
            'preceded_by_yellow_card' => $this->precededByYellowCard,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'issued_to' => ['required', 'string', 'in:head_coach,team_official,player'],
            'player_id' => ['nullable', 'string'],
            'cap_number' => ['nullable', 'integer'],
            'preceded_by_yellow_card' => ['required', 'boolean'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            issuedTo: $data['issued_to'],
            playerId: $data['player_id'] ?? null,
            capNumber: $data['cap_number'] ?? null,
            precededByYellowCard: $data['preceded_by_yellow_card'],
        );
    }
}
