<?php

namespace ChukkaWp\ChukkaSpec\Database\Seeders;

use ChukkaWp\ChukkaSpec\Models\RuleSet;
use Illuminate\Database\Seeder;

class RuleSetSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
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
        ];

        $ruleSets = [
            [
                'name' => 'World Aquatics 2025',
            ],
            [
                'name' => 'FINA 2022–2024',
                'possession_time_seconds' => 30,
                'second_possession_time_seconds' => 20,
            ],
            [
                'name' => 'Newcastle Club Comp U12',
                'running_time' => true,
                'possession_clock_enabled' => false,
                'cap_number_scheme' => 'open',
            ],
            [
                'name' => 'Newcastle Club Comp U14+',
                'running_time' => true,
                'cap_number_scheme' => 'open',
            ],
            [
                'name' => 'Newcastle Club Comp Finals',
                'cap_number_scheme' => 'open',
            ],
        ];

        foreach ($ruleSets as $ruleSet) {
            $name = $ruleSet['name'];
            unset($ruleSet['name']);

            RuleSet::firstOrCreate(
                ['name' => $name, 'is_bundled' => true],
                array_merge($defaults, $ruleSet),
            );
        }
    }
}
