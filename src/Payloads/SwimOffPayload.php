<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class SwimOffPayload extends Payload
{
    public function __construct(
        public readonly string $winningTeamId,
    ) {}

    public function toArray(): array
    {
        return [
            'winning_team_id' => $this->winningTeamId,
        ];
    }

    public static function rules(): array
    {
        return [
            'winning_team_id' => ['required', 'string'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            winningTeamId: $data['winning_team_id'],
        );
    }
}
