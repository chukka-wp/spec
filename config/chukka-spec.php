<?php

return [
    'models' => [
        'club' => \ChukkaWp\ChukkaSpec\Models\Club::class,
        'team' => \ChukkaWp\ChukkaSpec\Models\Team::class,
        'player' => \ChukkaWp\ChukkaSpec\Models\Player::class,
        'team_membership' => \ChukkaWp\ChukkaSpec\Models\TeamMembership::class,
        'rule_set' => \ChukkaWp\ChukkaSpec\Models\RuleSet::class,
        'match' => \ChukkaWp\ChukkaSpec\Models\MatchModel::class,
        'roster_entry' => \ChukkaWp\ChukkaSpec\Models\RosterEntry::class,
        'match_official' => \ChukkaWp\ChukkaSpec\Models\MatchOfficial::class,
        'event' => \ChukkaWp\ChukkaSpec\Models\Event::class,
    ],
];
