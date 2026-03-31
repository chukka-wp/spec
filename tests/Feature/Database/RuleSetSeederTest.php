<?php

use ChukkaWp\ChukkaSpec\Database\Seeders\RuleSetSeeder;
use ChukkaWp\ChukkaSpec\Enums\CapNumberScheme;
use ChukkaWp\ChukkaSpec\Models\RuleSet;

it('creates five bundled rule sets', function () {
    (new RuleSetSeeder)->run();

    expect(RuleSet::where('is_bundled', true)->count())->toBe(5);
});

it('creates World Aquatics 2025 with all defaults', function () {
    (new RuleSetSeeder)->run();

    $ruleSet = RuleSet::where('name', 'World Aquatics 2025')->first();

    expect($ruleSet)->not->toBeNull()
        ->and($ruleSet->is_bundled)->toBeTrue()
        ->and($ruleSet->periods)->toBe(4)
        ->and($ruleSet->period_duration_seconds)->toBe(480)
        ->and($ruleSet->running_time)->toBeFalse()
        ->and($ruleSet->interval_duration_seconds)->toBe(120)
        ->and($ruleSet->halftime_duration_seconds)->toBe(300)
        ->and($ruleSet->possession_clock_enabled)->toBeTrue()
        ->and($ruleSet->possession_time_seconds)->toBe(28)
        ->and($ruleSet->second_possession_time_seconds)->toBe(18)
        ->and($ruleSet->exclusion_duration_seconds)->toBe(20)
        ->and($ruleSet->violent_action_exclusion_duration_seconds)->toBe(240)
        ->and($ruleSet->personal_foul_limit)->toBe(3)
        ->and($ruleSet->foul_limit_enforced)->toBeTrue()
        ->and($ruleSet->timeouts_per_team)->toBe(2)
        ->and($ruleSet->timeout_duration_seconds)->toBe(60)
        ->and($ruleSet->overtime_period_duration_seconds)->toBe(180)
        ->and($ruleSet->players_per_team)->toBe(14)
        ->and($ruleSet->max_players_in_water)->toBe(7)
        ->and($ruleSet->max_goalkeepers)->toBe(2)
        ->and($ruleSet->cap_number_scheme)->toBe(CapNumberScheme::Sequential);
});

it('creates FINA 2022-2024 with longer possession times', function () {
    (new RuleSetSeeder)->run();

    $ruleSet = RuleSet::where('name', 'FINA 2022–2024')->first();

    expect($ruleSet)->not->toBeNull()
        ->and($ruleSet->is_bundled)->toBeTrue()
        ->and($ruleSet->possession_time_seconds)->toBe(30)
        ->and($ruleSet->second_possession_time_seconds)->toBe(20)
        ->and($ruleSet->running_time)->toBeFalse()
        ->and($ruleSet->possession_clock_enabled)->toBeTrue()
        ->and($ruleSet->cap_number_scheme)->toBe(CapNumberScheme::Sequential);
});

it('creates Newcastle Club Comp U12 with running time and no possession clock', function () {
    (new RuleSetSeeder)->run();

    $ruleSet = RuleSet::where('name', 'Newcastle Club Comp U12')->first();

    expect($ruleSet)->not->toBeNull()
        ->and($ruleSet->is_bundled)->toBeTrue()
        ->and($ruleSet->running_time)->toBeTrue()
        ->and($ruleSet->possession_clock_enabled)->toBeFalse()
        ->and($ruleSet->cap_number_scheme)->toBe(CapNumberScheme::Open);
});

it('creates Newcastle Club Comp U14+ with running time and open caps', function () {
    (new RuleSetSeeder)->run();

    $ruleSet = RuleSet::where('name', 'Newcastle Club Comp U14+')->first();

    expect($ruleSet)->not->toBeNull()
        ->and($ruleSet->is_bundled)->toBeTrue()
        ->and($ruleSet->running_time)->toBeTrue()
        ->and($ruleSet->possession_clock_enabled)->toBeTrue()
        ->and($ruleSet->cap_number_scheme)->toBe(CapNumberScheme::Open);
});

it('creates Newcastle Club Comp Finals with open caps and stopped time', function () {
    (new RuleSetSeeder)->run();

    $ruleSet = RuleSet::where('name', 'Newcastle Club Comp Finals')->first();

    expect($ruleSet)->not->toBeNull()
        ->and($ruleSet->is_bundled)->toBeTrue()
        ->and($ruleSet->running_time)->toBeFalse()
        ->and($ruleSet->possession_clock_enabled)->toBeTrue()
        ->and($ruleSet->cap_number_scheme)->toBe(CapNumberScheme::Open);
});

it('is idempotent when run multiple times', function () {
    (new RuleSetSeeder)->run();
    (new RuleSetSeeder)->run();

    expect(RuleSet::where('is_bundled', true)->count())->toBe(5);
});
