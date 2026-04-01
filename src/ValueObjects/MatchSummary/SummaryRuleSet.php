<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class SummaryRuleSet
{
    public function __construct(
        public string $name,
        public int $periods,
        public int $periodDurationSeconds,
        public ?int $overtimeDurationSeconds,
        public int $exclusionDurationSeconds,
        public ?int $foulLimit,
        public bool $possessionClockEnabled,
        public ?int $possessionTimeSeconds,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'periods' => $this->periods,
            'period_duration_seconds' => $this->periodDurationSeconds,
            'overtime_duration_seconds' => $this->overtimeDurationSeconds,
            'exclusion_duration_seconds' => $this->exclusionDurationSeconds,
            'foul_limit' => $this->foulLimit,
            'possession_clock_enabled' => $this->possessionClockEnabled,
            'possession_time_seconds' => $this->possessionTimeSeconds,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            periods: $data['periods'],
            periodDurationSeconds: $data['period_duration_seconds'],
            overtimeDurationSeconds: $data['overtime_duration_seconds'] ?? null,
            exclusionDurationSeconds: $data['exclusion_duration_seconds'],
            foulLimit: $data['foul_limit'] ?? null,
            possessionClockEnabled: $data['possession_clock_enabled'],
            possessionTimeSeconds: $data['possession_time_seconds'] ?? null,
        );
    }
}
