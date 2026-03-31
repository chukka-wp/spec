<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class ViolentActionExclusionPayload extends Payload
{
    public function __construct(
        public readonly string $offendingTeamId,
        public readonly string $offendingPlayerId,
        public readonly int $offendingCapNumber,
        public readonly int $substituteEligibleAfterSeconds,
        public readonly bool $penaltyThrowAwarded,
        public readonly bool $personalFoulRecorded,
    ) {}

    public function toArray(): array
    {
        return [
            'offending_team_id' => $this->offendingTeamId,
            'offending_player_id' => $this->offendingPlayerId,
            'offending_cap_number' => $this->offendingCapNumber,
            'substitute_eligible_after_seconds' => $this->substituteEligibleAfterSeconds,
            'penalty_throw_awarded' => $this->penaltyThrowAwarded,
            'personal_foul_recorded' => $this->personalFoulRecorded,
        ];
    }

    public static function rules(): array
    {
        return [
            'offending_team_id' => ['required', 'string'],
            'offending_player_id' => ['required', 'string'],
            'offending_cap_number' => ['required', 'integer'],
            'substitute_eligible_after_seconds' => ['required', 'integer'],
            'penalty_throw_awarded' => ['required', 'boolean'],
            'personal_foul_recorded' => ['required', 'boolean'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            offendingTeamId: $data['offending_team_id'],
            offendingPlayerId: $data['offending_player_id'],
            offendingCapNumber: $data['offending_cap_number'],
            substituteEligibleAfterSeconds: $data['substitute_eligible_after_seconds'],
            penaltyThrowAwarded: $data['penalty_throw_awarded'],
            personalFoulRecorded: $data['personal_foul_recorded'],
        );
    }
}
