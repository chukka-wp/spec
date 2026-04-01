<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class SummaryExclusion
{
    public function __construct(
        public int $period,
        public int $periodClockSeconds,
        public string $teamId,
        public string $playerId,
        public int $capNumber,
        public string $type,
        public int $durationSeconds,
        public bool $personalFoulRecorded,
    ) {}

    public function toArray(): array
    {
        return [
            'period' => $this->period,
            'period_clock_seconds' => $this->periodClockSeconds,
            'team_id' => $this->teamId,
            'player_id' => $this->playerId,
            'cap_number' => $this->capNumber,
            'type' => $this->type,
            'duration_seconds' => $this->durationSeconds,
            'personal_foul_recorded' => $this->personalFoulRecorded,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            period: $data['period'],
            periodClockSeconds: $data['period_clock_seconds'],
            teamId: $data['team_id'],
            playerId: $data['player_id'],
            capNumber: $data['cap_number'],
            type: $data['type'],
            durationSeconds: $data['duration_seconds'],
            personalFoulRecorded: $data['personal_foul_recorded'],
        );
    }
}
