<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

final class SimultaneousExclusionPayload extends Payload
{
    public function __construct(
        public readonly string $homePlayerId,
        public readonly int $homeCapNumber,
        public readonly string $awayPlayerId,
        public readonly int $awayCapNumber,
        public readonly string $possessionAtExclusion,
        public readonly int $exclusionDurationSeconds,
    ) {}

    public function toArray(): array
    {
        return [
            'home_player_id' => $this->homePlayerId,
            'home_cap_number' => $this->homeCapNumber,
            'away_player_id' => $this->awayPlayerId,
            'away_cap_number' => $this->awayCapNumber,
            'possession_at_exclusion' => $this->possessionAtExclusion,
            'exclusion_duration_seconds' => $this->exclusionDurationSeconds,
        ];
    }

    public static function rules(): array
    {
        return [
            'home_player_id' => ['required', 'string'],
            'home_cap_number' => ['required', 'integer'],
            'away_player_id' => ['required', 'string'],
            'away_cap_number' => ['required', 'integer'],
            'possession_at_exclusion' => ['required', 'string', 'in:home,away,none'],
            'exclusion_duration_seconds' => ['required', 'integer'],
        ];
    }

    protected static function hydrate(array $data): static
    {
        return new self(
            homePlayerId: $data['home_player_id'],
            homeCapNumber: $data['home_cap_number'],
            awayPlayerId: $data['away_player_id'],
            awayCapNumber: $data['away_cap_number'],
            possessionAtExclusion: $data['possession_at_exclusion'],
            exclusionDurationSeconds: $data['exclusion_duration_seconds'],
        );
    }
}
