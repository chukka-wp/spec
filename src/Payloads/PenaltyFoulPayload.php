<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class PenaltyFoulPayload extends Payload
{
    public function __construct(
        public readonly string $offendingTeamId,
        public readonly ?string $offendingPlayerId,
        public readonly ?int $offendingCapNumber,
        public readonly ?string $foulSubtype,
        public readonly bool $personalFoulRecorded,
        public readonly ?bool $alsoExcludedForGame,
    ) {}

    public function toArray(): array
    {
        return [
            'offending_team_id' => $this->offendingTeamId,
            'offending_player_id' => $this->offendingPlayerId,
            'offending_cap_number' => $this->offendingCapNumber,
            'foul_subtype' => $this->foulSubtype,
            'personal_foul_recorded' => $this->personalFoulRecorded,
            'also_excluded_for_game' => $this->alsoExcludedForGame,
        ];
    }

    public static function rules(): array
    {
        return [
            'offending_team_id' => ['required', 'string'],
            'offending_player_id' => ['nullable', 'string'],
            'offending_cap_number' => ['nullable', 'integer'],
            'foul_subtype' => ['nullable', 'string'],
            'personal_foul_recorded' => ['required', 'boolean'],
            'also_excluded_for_game' => ['nullable', 'boolean'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            offendingTeamId: $data['offending_team_id'],
            offendingPlayerId: $data['offending_player_id'] ?? null,
            offendingCapNumber: $data['offending_cap_number'] ?? null,
            foulSubtype: $data['foul_subtype'] ?? null,
            personalFoulRecorded: $data['personal_foul_recorded'],
            alsoExcludedForGame: $data['also_excluded_for_game'] ?? null,
        );
    }
}
