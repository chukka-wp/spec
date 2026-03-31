<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class GoalkeeperSubstitutionPayload extends Payload
{
    public function __construct(
        public readonly string $teamId,
        public readonly string $outgoingPlayerId,
        public readonly string $incomingPlayerId,
        public readonly int $outgoingCapNumber,
        public readonly int $incomingCapNumber,
    ) {}

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'outgoing_player_id' => $this->outgoingPlayerId,
            'incoming_player_id' => $this->incomingPlayerId,
            'outgoing_cap_number' => $this->outgoingCapNumber,
            'incoming_cap_number' => $this->incomingCapNumber,
        ];
    }

    public static function rules(): array
    {
        return [
            'team_id' => ['required', 'string'],
            'outgoing_player_id' => ['required', 'string'],
            'incoming_player_id' => ['required', 'string'],
            'outgoing_cap_number' => ['required', 'integer'],
            'incoming_cap_number' => ['required', 'integer'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            teamId: $data['team_id'],
            outgoingPlayerId: $data['outgoing_player_id'],
            incomingPlayerId: $data['incoming_player_id'],
            outgoingCapNumber: $data['outgoing_cap_number'],
            incomingCapNumber: $data['incoming_cap_number'],
        );
    }
}
