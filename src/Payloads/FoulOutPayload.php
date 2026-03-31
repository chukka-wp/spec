<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class FoulOutPayload extends Payload
{
    public function __construct(
        public readonly string $playerId,
        public readonly string $teamId,
        public readonly int $capNumber,
        public readonly int $foulCount,
        public readonly bool $substituteImmediately,
        public readonly bool $enforced,
    ) {}

    public function toArray(): array
    {
        return [
            'player_id' => $this->playerId,
            'team_id' => $this->teamId,
            'cap_number' => $this->capNumber,
            'foul_count' => $this->foulCount,
            'substitute_immediately' => $this->substituteImmediately,
            'enforced' => $this->enforced,
        ];
    }

    public static function rules(): array
    {
        return [
            'player_id' => ['required', 'string'],
            'team_id' => ['required', 'string'],
            'cap_number' => ['required', 'integer'],
            'foul_count' => ['required', 'integer'],
            'substitute_immediately' => ['required', 'boolean'],
            'enforced' => ['required', 'boolean'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            playerId: $data['player_id'],
            teamId: $data['team_id'],
            capNumber: $data['cap_number'],
            foulCount: $data['foul_count'],
            substituteImmediately: $data['substitute_immediately'],
            enforced: $data['enforced'],
        );
    }
}
