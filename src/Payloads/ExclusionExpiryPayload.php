<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class ExclusionExpiryPayload extends Payload
{
    public function __construct(
        public readonly string $playerId,
        public readonly string $teamId,
        public readonly string $reason,
    ) {}

    public function toArray(): array
    {
        return [
            'player_id' => $this->playerId,
            'team_id' => $this->teamId,
            'reason' => $this->reason,
        ];
    }

    public static function rules(): array
    {
        return [
            'player_id' => ['required', 'string'],
            'team_id' => ['required', 'string'],
            'reason' => ['required', 'string', 'in:time_elapsed,goal_scored,team_regained_possession,free_throw_awarded'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            playerId: $data['player_id'],
            teamId: $data['team_id'],
            reason: $data['reason'],
        );
    }
}
