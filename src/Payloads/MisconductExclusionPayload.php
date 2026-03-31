<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class MisconductExclusionPayload extends Payload
{
    public function __construct(
        public readonly string $offendingTeamId,
        public readonly string $offendingPlayerId,
        public readonly int $offendingCapNumber,
        public readonly bool $substituteImmediately,
    ) {}

    public function toArray(): array
    {
        return [
            'offending_team_id' => $this->offendingTeamId,
            'offending_player_id' => $this->offendingPlayerId,
            'offending_cap_number' => $this->offendingCapNumber,
            'substitute_immediately' => $this->substituteImmediately,
        ];
    }

    public static function rules(): array
    {
        return [
            'offending_team_id' => ['required', 'string'],
            'offending_player_id' => ['required', 'string'],
            'offending_cap_number' => ['required', 'integer'],
            'substitute_immediately' => ['required', 'boolean'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            offendingTeamId: $data['offending_team_id'],
            offendingPlayerId: $data['offending_player_id'],
            offendingCapNumber: $data['offending_cap_number'],
            substituteImmediately: $data['substitute_immediately'],
        );
    }
}
