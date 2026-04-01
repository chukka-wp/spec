<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary;

final readonly class MatchSummary
{
    public const SPEC_VERSION = '1.0';

    /**
     * @param array<int, SummaryOfficial> $officials
     * @param array<int, SummaryPeriodScore> $periodScores
     * @param array<int, SummaryGoal> $goals
     * @param array<int, SummaryExclusion> $exclusions
     * @param array<int, SummaryPenalty> $penalties
     * @param array<int, SummaryCard> $cards
     * @param array<int, SummaryTimeout> $timeouts
     */
    public function __construct(
        public string $specVersion,
        public string $matchId,
        public string $status,
        public ?string $venue,
        public ?string $scheduledAt,
        public ?string $completedAt,
        public SummaryRuleSet $ruleSet,
        public SummaryTeam $homeTeam,
        public SummaryTeam $awayTeam,
        public array $officials,
        public int $homeScore,
        public int $awayScore,
        public array $periodScores,
        public array $goals,
        public array $exclusions,
        public array $penalties,
        public array $cards,
        public array $timeouts,
        public ?SummaryShootout $shootout = null,
    ) {}

    public function toArray(): array
    {
        $data = [
            'spec_version' => $this->specVersion,
            'match_id' => $this->matchId,
            'status' => $this->status,
            'venue' => $this->venue,
            'scheduled_at' => $this->scheduledAt,
            'completed_at' => $this->completedAt,
            'rule_set' => $this->ruleSet->toArray(),
            'home_team' => $this->homeTeam->toArray(),
            'away_team' => $this->awayTeam->toArray(),
            'officials' => array_map(fn (SummaryOfficial $o) => $o->toArray(), $this->officials),
            'home_score' => $this->homeScore,
            'away_score' => $this->awayScore,
            'period_scores' => array_map(fn (SummaryPeriodScore $ps) => $ps->toArray(), $this->periodScores),
            'goals' => array_map(fn (SummaryGoal $g) => $g->toArray(), $this->goals),
            'exclusions' => array_map(fn (SummaryExclusion $e) => $e->toArray(), $this->exclusions),
            'penalties' => array_map(fn (SummaryPenalty $p) => $p->toArray(), $this->penalties),
            'cards' => array_map(fn (SummaryCard $c) => $c->toArray(), $this->cards),
            'timeouts' => array_map(fn (SummaryTimeout $t) => $t->toArray(), $this->timeouts),
        ];

        if ($this->shootout) {
            $data['shootout'] = $this->shootout->toArray();
        }

        return $data;
    }

    public function toJson(int $flags = 0): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR | $flags);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            specVersion: $data['spec_version'] ?? self::SPEC_VERSION,
            matchId: $data['match_id'],
            status: $data['status'],
            venue: $data['venue'] ?? null,
            scheduledAt: $data['scheduled_at'] ?? null,
            completedAt: $data['completed_at'] ?? null,
            ruleSet: SummaryRuleSet::fromArray($data['rule_set']),
            homeTeam: SummaryTeam::fromArray($data['home_team']),
            awayTeam: SummaryTeam::fromArray($data['away_team']),
            officials: array_map(fn (array $o) => SummaryOfficial::fromArray($o), $data['officials'] ?? []),
            homeScore: $data['home_score'],
            awayScore: $data['away_score'],
            periodScores: array_map(fn (array $ps) => SummaryPeriodScore::fromArray($ps), $data['period_scores'] ?? []),
            goals: array_map(fn (array $g) => SummaryGoal::fromArray($g), $data['goals'] ?? []),
            exclusions: array_map(fn (array $e) => SummaryExclusion::fromArray($e), $data['exclusions'] ?? []),
            penalties: array_map(fn (array $p) => SummaryPenalty::fromArray($p), $data['penalties'] ?? []),
            cards: array_map(fn (array $c) => SummaryCard::fromArray($c), $data['cards'] ?? []),
            timeouts: array_map(fn (array $t) => SummaryTimeout::fromArray($t), $data['timeouts'] ?? []),
            shootout: isset($data['shootout']) ? SummaryShootout::fromArray($data['shootout']) : null,
        );
    }
}
