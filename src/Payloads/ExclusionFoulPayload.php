<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class ExclusionFoulPayload extends Payload
{
    public function __construct(
        public readonly string $offendingTeamId,
        public readonly string $offendingPlayerId,
        public readonly int $offendingCapNumber,
        public readonly ?string $foulSubtype,
        public readonly ?bool $isAlsoPenalty,
        public readonly int $exclusionDurationSeconds,
        public readonly bool $personalFoulRecorded,
    ) {}

    public function toArray(): array
    {
        return [
            'offending_team_id' => $this->offendingTeamId,
            'offending_player_id' => $this->offendingPlayerId,
            'offending_cap_number' => $this->offendingCapNumber,
            'foul_subtype' => $this->foulSubtype,
            'is_also_penalty' => $this->isAlsoPenalty,
            'exclusion_duration_seconds' => $this->exclusionDurationSeconds,
            'personal_foul_recorded' => $this->personalFoulRecorded,
        ];
    }

    public static function rules(): array
    {
        return [
            'offending_team_id' => ['required', 'string'],
            'offending_player_id' => ['required', 'string'],
            'offending_cap_number' => ['required', 'integer'],
            'foul_subtype' => ['nullable', 'string'],
            'is_also_penalty' => ['nullable', 'boolean'],
            'exclusion_duration_seconds' => ['required', 'integer'],
            'personal_foul_recorded' => ['required', 'boolean'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            offendingTeamId: $data['offending_team_id'],
            offendingPlayerId: $data['offending_player_id'],
            offendingCapNumber: $data['offending_cap_number'],
            foulSubtype: $data['foul_subtype'] ?? null,
            isAlsoPenalty: $data['is_also_penalty'] ?? null,
            exclusionDurationSeconds: $data['exclusion_duration_seconds'],
            personalFoulRecorded: $data['personal_foul_recorded'],
        );
    }
}
