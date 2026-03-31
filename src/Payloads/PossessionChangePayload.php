<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class PossessionChangePayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly string $reason,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'reason' => $this->reason,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'reason' => ['required', 'string', 'in:goal_scored,free_throw,turnover,exclusion_expiry,timeout_end,period_start'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            reason: $data['reason'],
        );
    }
}
