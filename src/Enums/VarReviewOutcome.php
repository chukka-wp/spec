<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum VarReviewOutcome: string
{
    case GoalConfirmed = 'goal_confirmed';
    case GoalDisallowed = 'goal_disallowed';
    case NoChange = 'no_change';
    case ViolentActionSanctioned = 'violent_action_sanctioned';

    public function label(): string
    {
        return match ($this) {
            self::GoalConfirmed => 'Goal Confirmed',
            self::GoalDisallowed => 'Goal Disallowed',
            self::NoChange => 'No Change',
            self::ViolentActionSanctioned => 'Violent Action Sanctioned',
        };
    }
}
