<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class PossessionClockResetPayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly int $newValueSeconds,
        public readonly string $mode,
        public readonly string $reason,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'new_value_seconds' => $this->newValueSeconds,
            'mode' => $this->mode,
            'reason' => $this->reason,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'new_value_seconds' => ['required', 'integer'],
            'mode' => ['required', 'string', 'in:standard,reduced'],
            'reason' => ['required', 'string', 'in:new_possession,shot_rebound_attacking,exclusion_foul,corner_throw,penalty_throw_no_change,neutral_throw,goal_throw,timeout_end'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            newValueSeconds: $data['new_value_seconds'],
            mode: $data['mode'],
            reason: $data['reason'],
        );
    }
}
