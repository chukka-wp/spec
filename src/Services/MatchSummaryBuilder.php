<?php

namespace ChukkaWp\ChukkaSpec\Services;

use ChukkaWp\ChukkaSpec\Enums\EventType;
use ChukkaWp\ChukkaSpec\Handlers\CorrectionPreFilter;
use ChukkaWp\ChukkaSpec\Models\MatchModel;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\MatchSummary;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryCard;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryExclusion;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryGoal;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryOfficial;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryPenalty;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryPeriodScore;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryPlayer;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryRuleSet;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryShootout;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryShootoutShot;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryTeam;
use ChukkaWp\ChukkaSpec\ValueObjects\MatchSummary\SummaryTimeout;

class MatchSummaryBuilder
{
    public function buildForMatch(MatchModel $match): MatchSummary
    {
        $relations = ['events', 'rosterEntries', 'officials'];

        if ($match->rule_set_id) {
            $relations[] = 'ruleSet';
        }

        if ($match->home_team_id && method_exists($match, 'homeTeam')) {
            $relations[] = 'homeTeam.club';
            $relations[] = 'awayTeam.club';
        }

        if (! $match->rosterEntries?->first()?->player_name && method_exists($match, 'rosterEntries')) {
            $relations[] = 'rosterEntries.player';
        }

        $match->loadMissing($relations);

        $preFilter = new CorrectionPreFilter;
        $result = $preFilter->filter($match->events);
        $events = $result['events'];

        $goals = [];
        $exclusions = [];
        $penalties = [];
        $cards = [];
        $timeouts = [];
        $periodScores = [];
        $shootoutShots = [];

        $homeScore = 0;
        $awayScore = 0;
        $shootoutHomeScore = 0;
        $shootoutAwayScore = 0;
        $completedAt = null;

        /** @var array<string, int> $playerGoals */
        $playerGoals = [];
        /** @var array<string, int> $playerFouls */
        $playerFouls = [];
        /** @var array<string, int> $playerExclusions */
        $playerExclusions = [];
        /** @var array<string, bool> $playersFouledOut */
        $playersFouledOut = [];

        foreach ($events as $event) {
            $payload = $event->payload ?? [];

            match ($event->type) {
                EventType::Goal => $this->processGoal($event, $payload, $goals, $homeScore, $awayScore, $playerGoals),
                EventType::ExclusionFoul => $this->processExclusionFoul($event, $payload, $exclusions, $playerExclusions),
                EventType::ViolentActionExclusion => $this->processViolentAction($event, $payload, $exclusions, $playerExclusions),
                EventType::MisconductExclusion => $this->processMisconduct($event, $payload, $exclusions, $playerExclusions),
                EventType::PenaltyThrowTaken => $this->processPenalty($event, $payload, $penalties),
                EventType::YellowCard => $this->processCard($event, $payload, 'yellow', $cards),
                EventType::RedCard => $this->processCard($event, $payload, 'red', $cards),
                EventType::TimeoutStart => $this->processTimeout($event, $payload, $timeouts),
                EventType::PersonalFoulRecorded => $this->processPersonalFoul($payload, $playerFouls),
                EventType::FoulOut => $this->processFoulOut($payload, $playersFouledOut),
                EventType::PeriodEnd => $this->processPeriodEnd($event, $homeScore, $awayScore, $periodScores),
                EventType::ShootoutShot => $this->processShootoutShot($payload, $shootoutShots, $shootoutHomeScore, $shootoutAwayScore),
                EventType::MatchEnd, EventType::MatchAbandoned => $completedAt = $event->recorded_at?->toIso8601String(),
                default => null,
            };
        }

        $hasSide = $match->rosterEntries->first()?->side !== null;

        if ($hasSide) {
            $homeRoster = $match->rosterEntries->where('side', 'home');
            $awayRoster = $match->rosterEntries->where('side', 'away');
        } else {
            $rosterByTeam = $match->rosterEntries->groupBy('team_id');
            $homeRoster = $rosterByTeam->get($match->home_team_id, collect());
            $awayRoster = $rosterByTeam->get($match->away_team_id, collect());
        }

        $homeTeam = $this->buildTeam(
            $match->homeTeam ?? null,
            $match->home_team_name,
            $match->home_cap_colour,
            $match->home_team_id ?? $match->home_external_team_id,
            $homeRoster,
            $playerGoals,
            $playerFouls,
            $playerExclusions,
            $playersFouledOut,
        );

        $awayTeam = $this->buildTeam(
            $match->awayTeam ?? null,
            $match->away_team_name,
            $match->away_cap_colour,
            $match->away_team_id ?? $match->away_external_team_id,
            $awayRoster,
            $playerGoals,
            $playerFouls,
            $playerExclusions,
            $playersFouledOut,
        );

        $officials = $match->officials->map(fn ($official) => new SummaryOfficial(
            role: $official->role->value,
            name: $official->name,
            userId: $official->user_id,
            teamId: $official->team_id,
        ))->all();

        $ruleSet = $match->ruleSet ?? (object) ($match->rule_config ?? []);

        $shootout = count($shootoutShots) > 0
            ? new SummaryShootout(
                homeScore: $shootoutHomeScore,
                awayScore: $shootoutAwayScore,
                shots: $shootoutShots,
            )
            : null;

        return new MatchSummary(
            specVersion: MatchSummary::SPEC_VERSION,
            matchId: $match->id,
            status: $match->status->value,
            venue: $match->venue,
            scheduledAt: $match->scheduled_at?->toIso8601String(),
            completedAt: $completedAt,
            ruleSet: new SummaryRuleSet(
                name: $ruleSet->name,
                periods: $ruleSet->periods,
                periodDurationSeconds: $ruleSet->period_duration_seconds,
                overtimeDurationSeconds: $ruleSet->overtime_period_duration_seconds,
                exclusionDurationSeconds: $ruleSet->exclusion_duration_seconds,
                foulLimit: $ruleSet->foul_limit_enforced ? $ruleSet->personal_foul_limit : null,
                possessionClockEnabled: $ruleSet->possession_clock_enabled,
                possessionTimeSeconds: $ruleSet->possession_clock_enabled ? $ruleSet->possession_time_seconds : null,
            ),
            homeTeam: $homeTeam,
            awayTeam: $awayTeam,
            officials: $officials,
            homeScore: $homeScore,
            awayScore: $awayScore,
            periodScores: $periodScores,
            goals: $goals,
            exclusions: $exclusions,
            penalties: $penalties,
            cards: $cards,
            timeouts: $timeouts,
            shootout: $shootout,
        );
    }

    private function processGoal(
        object $event,
        array $payload,
        array &$goals,
        int &$homeScore,
        int &$awayScore,
        array &$playerGoals,
    ): void {
        $homeScore = $payload['home_score_after'];
        $awayScore = $payload['away_score_after'];

        $goals[] = new SummaryGoal(
            period: $event->period,
            periodClockSeconds: $event->period_clock_seconds,
            teamId: $payload['team_id'],
            playerId: $payload['player_id'] ?? null,
            capNumber: $payload['cap_number'] ?? null,
            method: $payload['method'] ?? null,
            homeScoreAfter: $payload['home_score_after'],
            awayScoreAfter: $payload['away_score_after'],
        );

        if (isset($payload['player_id'])) {
            $playerGoals[$payload['player_id']] = ($playerGoals[$payload['player_id']] ?? 0) + 1;
        }
    }

    private function processExclusionFoul(
        object $event,
        array $payload,
        array &$exclusions,
        array &$playerExclusions,
    ): void {
        $exclusions[] = new SummaryExclusion(
            period: $event->period,
            periodClockSeconds: $event->period_clock_seconds,
            teamId: $payload['offending_team_id'],
            playerId: $payload['offending_player_id'],
            capNumber: $payload['offending_cap_number'],
            type: 'standard',
            durationSeconds: $payload['exclusion_duration_seconds'],
            personalFoulRecorded: $payload['personal_foul_recorded'],
        );

        $playerExclusions[$payload['offending_player_id']] = ($playerExclusions[$payload['offending_player_id']] ?? 0) + 1;
    }

    private function processViolentAction(
        object $event,
        array $payload,
        array &$exclusions,
        array &$playerExclusions,
    ): void {
        $exclusions[] = new SummaryExclusion(
            period: $event->period,
            periodClockSeconds: $event->period_clock_seconds,
            teamId: $payload['offending_team_id'],
            playerId: $payload['offending_player_id'],
            capNumber: $payload['offending_cap_number'],
            type: 'violent_action',
            durationSeconds: $payload['substitute_eligible_after_seconds'],
            personalFoulRecorded: $payload['personal_foul_recorded'],
        );

        $playerExclusions[$payload['offending_player_id']] = ($playerExclusions[$payload['offending_player_id']] ?? 0) + 1;
    }

    private function processMisconduct(
        object $event,
        array $payload,
        array &$exclusions,
        array &$playerExclusions,
    ): void {
        $exclusions[] = new SummaryExclusion(
            period: $event->period,
            periodClockSeconds: $event->period_clock_seconds,
            teamId: $payload['offending_team_id'],
            playerId: $payload['offending_player_id'],
            capNumber: $payload['offending_cap_number'],
            type: 'misconduct',
            durationSeconds: 0,
            personalFoulRecorded: false,
        );

        $playerExclusions[$payload['offending_player_id']] = ($playerExclusions[$payload['offending_player_id']] ?? 0) + 1;
    }

    private function processPenalty(object $event, array $payload, array &$penalties): void
    {
        $penalties[] = new SummaryPenalty(
            period: $event->period,
            periodClockSeconds: $event->period_clock_seconds,
            teamId: $payload['shooting_team_id'],
            playerId: $payload['shooter_player_id'] ?? null,
            capNumber: $payload['shooter_cap_number'] ?? null,
            outcome: $payload['outcome'],
        );
    }

    private function processCard(object $event, array $payload, string $colour, array &$cards): void
    {
        $cards[] = new SummaryCard(
            period: $event->period,
            periodClockSeconds: $event->period_clock_seconds,
            teamId: $payload['team_id'],
            colour: $colour,
            issuedTo: $payload['issued_to'],
            playerId: $payload['player_id'] ?? null,
            capNumber: $payload['cap_number'] ?? null,
        );
    }

    private function processTimeout(object $event, array $payload, array &$timeouts): void
    {
        $timeouts[] = new SummaryTimeout(
            period: $event->period,
            periodClockSeconds: $event->period_clock_seconds,
            teamId: $payload['team_id'],
        );
    }

    private function processPersonalFoul(array $payload, array &$playerFouls): void
    {
        $playerFouls[$payload['player_id']] = $payload['foul_count_after'];
    }

    private function processFoulOut(array $payload, array &$playersFouledOut): void
    {
        $playersFouledOut[$payload['player_id']] = true;
    }

    private function processPeriodEnd(
        object $event,
        int $homeScore,
        int $awayScore,
        array &$periodScores,
    ): void {
        $periodScores[] = new SummaryPeriodScore(
            period: $event->period,
            homeScore: $homeScore,
            awayScore: $awayScore,
        );
    }

    private function processShootoutShot(
        array $payload,
        array &$shootoutShots,
        int &$shootoutHomeScore,
        int &$shootoutAwayScore,
    ): void {
        $shootoutShots[] = new SummaryShootoutShot(
            teamId: $payload['team_id'],
            playerId: $payload['player_id'],
            capNumber: $payload['cap_number'],
            round: $payload['round'],
            outcome: $payload['outcome'],
        );

        $shootoutHomeScore = $payload['home_shootout_score_after'];
        $shootoutAwayScore = $payload['away_shootout_score_after'];
    }

    private function buildTeam(
        ?object $team,
        ?string $teamName,
        ?string $capColour,
        ?string $teamId,
        $rosterEntries,
        array $playerGoals,
        array $playerFouls,
        array $playerExclusions,
        array $playersFouledOut,
    ): SummaryTeam {
        $players = $rosterEntries->map(function ($entry) use ($playerGoals, $playerFouls, $playerExclusions, $playersFouledOut) {
            $playerId = $entry->player_id ?? $entry->external_player_id;
            $playerName = $entry->player?->preferred_name ?? $entry->player?->name ?? $entry->player_name ?? 'Unknown';

            return new SummaryPlayer(
                capNumber: $entry->cap_number,
                name: $playerName,
                role: $entry->role->value,
                isStarting: $entry->is_starting,
                goals: $playerGoals[$playerId] ?? 0,
                personalFouls: $playerFouls[$playerId] ?? 0,
                exclusions: $playerExclusions[$playerId] ?? 0,
                fouledOut: $playersFouledOut[$playerId] ?? false,
                playerId: $playerId,
            );
        })->sortBy('capNumber')->values()->all();

        return new SummaryTeam(
            teamId: $team?->id ?? $teamId,
            teamName: $team?->name ?? $teamName ?? 'Unknown',
            clubName: $team?->club?->name,
            capColour: $capColour,
            players: $players,
        );
    }
}
