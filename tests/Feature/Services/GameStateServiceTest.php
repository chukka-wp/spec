<?php

use ChukkaWp\ChukkaSpec\Enums\EventType;
use ChukkaWp\ChukkaSpec\Enums\ExclusionType;
use ChukkaWp\ChukkaSpec\Enums\GameStatus;
use ChukkaWp\ChukkaSpec\Enums\Possession;
use ChukkaWp\ChukkaSpec\Enums\PossessionClockMode;
use ChukkaWp\ChukkaSpec\Models\Club;
use ChukkaWp\ChukkaSpec\Models\Event;
use ChukkaWp\ChukkaSpec\Models\MatchModel;
use ChukkaWp\ChukkaSpec\Models\Player;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Models\Team;
use ChukkaWp\ChukkaSpec\Services\GameStateService;

function createMatchWithRuleSet(array $ruleSetOverrides = []): object
{
    $club = Club::create(['name' => 'Test Club']);

    $homeTeam = Team::create([
        'club_id' => $club->id,
        'name' => 'Home Team',
        'gender' => 'male',
    ]);

    $awayTeam = Team::create([
        'club_id' => $club->id,
        'name' => 'Away Team',
        'gender' => 'male',
    ]);

    $homePlayers = collect();
    $awayPlayers = collect();

    for ($i = 1; $i <= 3; $i++) {
        $homePlayers->push(Player::create([
            'club_id' => $club->id,
            'name' => "Home Player {$i}",
            'preferred_cap_number' => $i,
        ]));

        $awayPlayers->push(Player::create([
            'club_id' => $club->id,
            'name' => "Away Player {$i}",
            'preferred_cap_number' => $i,
        ]));
    }

    $ruleSet = RuleSet::create(array_merge([
        'name' => 'World Aquatics 2025',
        'is_bundled' => true,
        'periods' => 4,
        'period_duration_seconds' => 480,
        'running_time' => false,
        'interval_duration_seconds' => 120,
        'halftime_duration_seconds' => 300,
        'possession_clock_enabled' => true,
        'possession_time_seconds' => 28,
        'second_possession_time_seconds' => 18,
        'exclusion_duration_seconds' => 20,
        'violent_action_exclusion_duration_seconds' => 240,
        'personal_foul_limit' => 3,
        'foul_limit_enforced' => true,
        'timeouts_per_team' => 2,
        'timeout_duration_seconds' => 60,
        'overtime_period_duration_seconds' => 180,
        'players_per_team' => 14,
        'max_players_in_water' => 7,
        'max_goalkeepers' => 2,
        'cap_number_scheme' => 'sequential',
    ], $ruleSetOverrides));

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $homeTeam->id,
        'away_team_id' => $awayTeam->id,
        'status' => 'scheduled',
    ]);

    return (object) [
        'match' => $match,
        'home_team' => $homeTeam,
        'away_team' => $awayTeam,
        'rule_set' => $ruleSet,
        'home_players' => $homePlayers,
        'away_players' => $awayPlayers,
    ];
}

function appendEvent(
    MatchModel $match,
    EventType $type,
    int $sequence,
    int $period,
    int $clockSeconds,
    array $payload = [],
): Event {
    return Event::create([
        'match_id' => $match->id,
        'type' => $type->value,
        'sequence' => $sequence,
        'period' => $period,
        'period_clock_seconds' => $clockSeconds,
        'recorded_at' => now(),
        'payload' => ! empty($payload) ? $payload : null,
    ]);
}

function computeState(MatchModel $match): \ChukkaWp\ChukkaSpec\ValueObjects\GameState
{
    $service = new GameStateService();

    return $service->compute(
        $match->events()->orderBy('sequence')->get(),
        $match->ruleSet,
        $match->id,
        $match->home_team_id,
        $match->away_team_id,
    );
}

// ---------------------------------------------------------------------------
// Tests
// ---------------------------------------------------------------------------

it('computes initial state for empty match', function () {
    $fixture = createMatchWithRuleSet();

    $state = computeState($fixture->match);

    expect($state->status)->toBe(GameStatus::NotStarted)
        ->and($state->currentPeriod)->toBe(1)
        ->and($state->homeScore)->toBe(0)
        ->and($state->awayScore)->toBe(0)
        ->and($state->possession)->toBe(Possession::None)
        ->and($state->homeTimeoutsRemaining)->toBe(2)
        ->and($state->awayTimeoutsRemaining)->toBe(2)
        ->and($state->activeExclusions)->toBeEmpty()
        ->and($state->playerFoulCounts)->toBeEmpty()
        ->and($state->playersExcludedForGame)->toBeEmpty()
        ->and($state->periodClockSeconds)->toBe(480)
        ->and($state->possessionClockSeconds)->toBe(28)
        ->and($state->possessionClockMode)->toBe(PossessionClockMode::Standard)
        ->and($state->shootoutState)->toBeNull();
});

it('handles match start and period start', function () {
    $fixture = createMatchWithRuleSet();
    $match = $fixture->match;

    appendEvent($match, EventType::MatchStart, 1, 1, 480);
    appendEvent($match, EventType::PeriodStart, 2, 1, 480);

    $state = computeState($match);

    expect($state->status)->toBe(GameStatus::InProgress)
        ->and($state->currentPeriod)->toBe(1)
        ->and($state->periodClockSeconds)->toBe(480);
});

it('tracks goals correctly', function () {
    $fixture = createMatchWithRuleSet();
    $match = $fixture->match;

    appendEvent($match, EventType::MatchStart, 1, 1, 480);
    appendEvent($match, EventType::PeriodStart, 2, 1, 480);

    appendEvent($match, EventType::Goal, 3, 1, 420, [
        'team_id' => $fixture->home_team->id,
        'player_id' => $fixture->home_players->first()->id,
        'cap_number' => 1,
        'method' => 'field_goal',
        'home_score_after' => 1,
        'away_score_after' => 0,
    ]);

    appendEvent($match, EventType::Goal, 4, 1, 360, [
        'team_id' => $fixture->away_team->id,
        'player_id' => $fixture->away_players->first()->id,
        'cap_number' => 1,
        'method' => 'field_goal',
        'home_score_after' => 1,
        'away_score_after' => 1,
    ]);

    $state = computeState($match);

    expect($state->homeScore)->toBe(1)
        ->and($state->awayScore)->toBe(1);
});

it('handles exclusion foul and expiry', function () {
    $fixture = createMatchWithRuleSet();
    $match = $fixture->match;
    $excludedPlayer = $fixture->home_players->first();

    appendEvent($match, EventType::MatchStart, 1, 1, 480);
    appendEvent($match, EventType::PeriodStart, 2, 1, 480);

    appendEvent($match, EventType::ExclusionFoul, 3, 1, 400, [
        'offending_team_id' => $fixture->home_team->id,
        'offending_player_id' => $excludedPlayer->id,
        'offending_cap_number' => 1,
        'foul_subtype' => null,
        'is_also_penalty' => false,
        'exclusion_duration_seconds' => 20,
        'personal_foul_recorded' => false,
    ]);

    $stateAfterExclusion = computeState($match);

    expect($stateAfterExclusion->activeExclusions)->toHaveCount(1)
        ->and($stateAfterExclusion->activeExclusions[0]->playerId)->toBe($excludedPlayer->id)
        ->and($stateAfterExclusion->activeExclusions[0]->teamId)->toBe($fixture->home_team->id)
        ->and($stateAfterExclusion->activeExclusions[0]->remainingSeconds)->toBe(20)
        ->and($stateAfterExclusion->activeExclusions[0]->exclusionType)->toBe(ExclusionType::Standard);

    appendEvent($match, EventType::ExclusionExpiry, 4, 1, 380, [
        'player_id' => $excludedPlayer->id,
        'team_id' => $fixture->home_team->id,
        'reason' => 'time_elapsed',
    ]);

    $stateAfterExpiry = computeState($match);

    expect($stateAfterExpiry->activeExclusions)->toBeEmpty();
});

it('tracks personal fouls and foul out', function () {
    $fixture = createMatchWithRuleSet();
    $match = $fixture->match;
    $offender = $fixture->home_players->first();

    appendEvent($match, EventType::MatchStart, 1, 1, 480);
    appendEvent($match, EventType::PeriodStart, 2, 1, 480);

    // Three exclusion fouls that each trigger a personal foul recorded event
    $seq = 3;
    for ($foulNum = 1; $foulNum <= 3; $foulNum++) {
        $exclusionEvent = appendEvent($match, EventType::ExclusionFoul, $seq++, 1, 480 - ($foulNum * 30), [
            'offending_team_id' => $fixture->home_team->id,
            'offending_player_id' => $offender->id,
            'offending_cap_number' => 1,
            'foul_subtype' => null,
            'is_also_penalty' => false,
            'exclusion_duration_seconds' => 20,
            'personal_foul_recorded' => true,
        ]);

        appendEvent($match, EventType::PersonalFoulRecorded, $seq++, 1, 480 - ($foulNum * 30), [
            'player_id' => $offender->id,
            'team_id' => $fixture->home_team->id,
            'cap_number' => 1,
            'foul_count_after' => $foulNum,
            'triggered_by_event_id' => $exclusionEvent->id,
        ]);

        appendEvent($match, EventType::ExclusionExpiry, $seq++, 1, 480 - ($foulNum * 30) - 20, [
            'player_id' => $offender->id,
            'team_id' => $fixture->home_team->id,
            'reason' => 'time_elapsed',
        ]);
    }

    // Foul out after reaching 3 personal fouls
    appendEvent($match, EventType::FoulOut, $seq, 1, 380, [
        'player_id' => $offender->id,
        'team_id' => $fixture->home_team->id,
        'cap_number' => 1,
        'foul_count' => 3,
        'substitute_immediately' => true,
        'enforced' => true,
    ]);

    $state = computeState($match);

    expect($state->playerFoulCounts[$offender->id])->toBe(3)
        ->and($state->playersExcludedForGame)->toContain($offender->id);
});

it('handles period transitions', function () {
    $fixture = createMatchWithRuleSet();
    $match = $fixture->match;

    appendEvent($match, EventType::MatchStart, 1, 1, 480);
    appendEvent($match, EventType::PeriodStart, 2, 1, 480);
    appendEvent($match, EventType::PeriodEnd, 3, 1, 0);

    $stateAfterP1End = computeState($match);

    expect($stateAfterP1End->status)->toBe(GameStatus::PeriodBreak)
        ->and($stateAfterP1End->currentPeriod)->toBe(1);

    appendEvent($match, EventType::PeriodStart, 4, 2, 480);

    $stateAfterP2Start = computeState($match);

    expect($stateAfterP2Start->status)->toBe(GameStatus::InProgress)
        ->and($stateAfterP2Start->currentPeriod)->toBe(2)
        ->and($stateAfterP2Start->periodClockSeconds)->toBe(480);
});

it('handles halftime transition', function () {
    $fixture = createMatchWithRuleSet();
    $match = $fixture->match;

    appendEvent($match, EventType::MatchStart, 1, 1, 480);
    appendEvent($match, EventType::PeriodStart, 2, 1, 480);
    appendEvent($match, EventType::PeriodEnd, 3, 1, 0);
    appendEvent($match, EventType::PeriodStart, 4, 2, 480);
    appendEvent($match, EventType::PeriodEnd, 5, 2, 0);
    appendEvent($match, EventType::HalftimeStart, 6, 2, 0);

    $stateAtHalftime = computeState($match);

    expect($stateAtHalftime->status)->toBe(GameStatus::Halftime)
        ->and($stateAtHalftime->currentPeriod)->toBe(2);

    appendEvent($match, EventType::HalftimeEnd, 7, 2, 0);
    appendEvent($match, EventType::PeriodStart, 8, 3, 480);

    $stateAfterP3Start = computeState($match);

    expect($stateAfterP3Start->status)->toBe(GameStatus::InProgress)
        ->and($stateAfterP3Start->currentPeriod)->toBe(3)
        ->and($stateAfterP3Start->periodClockSeconds)->toBe(480);
});

it('voids a goal via correction', function () {
    $fixture = createMatchWithRuleSet();
    $match = $fixture->match;

    appendEvent($match, EventType::MatchStart, 1, 1, 480);
    appendEvent($match, EventType::PeriodStart, 2, 1, 480);

    $goalEvent = appendEvent($match, EventType::Goal, 3, 1, 420, [
        'team_id' => $fixture->home_team->id,
        'player_id' => $fixture->home_players->first()->id,
        'cap_number' => 1,
        'method' => 'field_goal',
        'home_score_after' => 1,
        'away_score_after' => 0,
    ]);

    $stateBeforeCorrection = computeState($match);

    expect($stateBeforeCorrection->homeScore)->toBe(1)
        ->and($stateBeforeCorrection->awayScore)->toBe(0);

    appendEvent($match, EventType::Correction, 4, 1, 420, [
        'corrects_event_id' => $goalEvent->id,
        'action' => 'void',
        'replacement_type' => null,
        'replacement_payload' => null,
        'reason' => 'Referee overturned goal',
    ]);

    $stateAfterCorrection = computeState($match);

    expect($stateAfterCorrection->homeScore)->toBe(0)
        ->and($stateAfterCorrection->awayScore)->toBe(0);
});

it('handles possession changes', function () {
    $fixture = createMatchWithRuleSet();
    $match = $fixture->match;

    appendEvent($match, EventType::MatchStart, 1, 1, 480);
    appendEvent($match, EventType::PeriodStart, 2, 1, 480);

    appendEvent($match, EventType::PossessionChange, 3, 1, 475, [
        'team_id' => $fixture->home_team->id,
        'reason' => 'period_start',
    ]);

    $state = computeState($match);

    expect($state->possession)->toBe(Possession::Home);

    appendEvent($match, EventType::PossessionChange, 4, 1, 450, [
        'team_id' => $fixture->away_team->id,
        'reason' => 'turnover',
    ]);

    $stateAfterChange = computeState($match);

    expect($stateAfterChange->possession)->toBe(Possession::Away);
});

it('handles possession clock reset', function () {
    $fixture = createMatchWithRuleSet();
    $match = $fixture->match;

    appendEvent($match, EventType::MatchStart, 1, 1, 480);
    appendEvent($match, EventType::PeriodStart, 2, 1, 480);

    appendEvent($match, EventType::PossessionClockReset, 3, 1, 470, [
        'team_id' => $fixture->home_team->id,
        'new_value_seconds' => 28,
        'mode' => 'standard',
        'reason' => 'new_possession',
    ]);

    $state = computeState($match);

    expect($state->possessionClockSeconds)->toBe(28)
        ->and($state->possessionClockMode)->toBe(PossessionClockMode::Standard);

    appendEvent($match, EventType::PossessionClockReset, 4, 1, 460, [
        'team_id' => $fixture->home_team->id,
        'new_value_seconds' => 18,
        'mode' => 'reduced',
        'reason' => 'shot_rebound_attacking',
    ]);

    $stateAfterReduced = computeState($match);

    expect($stateAfterReduced->possessionClockSeconds)->toBe(18)
        ->and($stateAfterReduced->possessionClockMode)->toBe(PossessionClockMode::Reduced);
});

it('respects possession clock disabled rule set', function () {
    $fixture = createMatchWithRuleSet([
        'possession_clock_enabled' => false,
    ]);
    $match = $fixture->match;

    appendEvent($match, EventType::MatchStart, 1, 1, 480);
    appendEvent($match, EventType::PeriodStart, 2, 1, 480);

    appendEvent($match, EventType::PossessionClockReset, 3, 1, 470, [
        'team_id' => $fixture->home_team->id,
        'new_value_seconds' => 28,
        'mode' => 'standard',
        'reason' => 'new_possession',
    ]);

    $state = computeState($match);

    expect($state->possessionClockSeconds)->toBeNull()
        ->and($state->possessionClockMode)->toBeNull();
});

it('clears active exclusions at period end', function () {
    $fixture = createMatchWithRuleSet();
    $match = $fixture->match;
    $excludedPlayer = $fixture->home_players->first();

    appendEvent($match, EventType::MatchStart, 1, 1, 480);
    appendEvent($match, EventType::PeriodStart, 2, 1, 480);

    appendEvent($match, EventType::ExclusionFoul, 3, 1, 10, [
        'offending_team_id' => $fixture->home_team->id,
        'offending_player_id' => $excludedPlayer->id,
        'offending_cap_number' => 1,
        'foul_subtype' => null,
        'is_also_penalty' => false,
        'exclusion_duration_seconds' => 20,
        'personal_foul_recorded' => false,
    ]);

    $stateDuringPeriod = computeState($match);

    expect($stateDuringPeriod->activeExclusions)->toHaveCount(1);

    appendEvent($match, EventType::PeriodEnd, 4, 1, 0);

    $stateAfterPeriodEnd = computeState($match);

    expect($stateAfterPeriodEnd->activeExclusions)->toBeEmpty();
});
