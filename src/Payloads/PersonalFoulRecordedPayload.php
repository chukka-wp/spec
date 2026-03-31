<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class PersonalFoulRecordedPayload extends Payload
{
    public function __construct(
        public readonly string $playerId,
        public readonly string $teamId,
        public readonly int $capNumber,
        public readonly int $foulCountAfter,
        public readonly string $triggeredByEventId,
    ) {}

    public function toArray(): array
    {
        return [
            'player_id' => $this->playerId,
            'team_id' => $this->teamId,
            'cap_number' => $this->capNumber,
            'foul_count_after' => $this->foulCountAfter,
            'triggered_by_event_id' => $this->triggeredByEventId,
        ];
    }

    public static function rules(): array
    {
        return [
            'player_id' => ['required', 'string'],
            'team_id' => ['required', 'string'],
            'cap_number' => ['required', 'integer'],
            'foul_count_after' => ['required', 'integer'],
            'triggered_by_event_id' => ['required', 'string'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            playerId: $data['player_id'],
            teamId: $data['team_id'],
            capNumber: $data['cap_number'],
            foulCountAfter: $data['foul_count_after'],
            triggeredByEventId: $data['triggered_by_event_id'],
        );
    }
}
