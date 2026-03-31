<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class SubstitutionPayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly array $playersOff,
        public readonly array $playersOn,
        public readonly string $substitutionType,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'players_off' => $this->playersOff,
            'players_on' => $this->playersOn,
            'substitution_type' => $this->substitutionType,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'players_off' => ['required', 'array'],
            'players_off.*' => ['required', 'string'],
            'players_on' => ['required', 'array'],
            'players_on.*' => ['required', 'string'],
            'substitution_type' => ['required', 'string', 'in:flying,standard,bleeding'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            playersOff: $data['players_off'],
            playersOn: $data['players_on'],
            substitutionType: $data['substitution_type'],
        );
    }
}
