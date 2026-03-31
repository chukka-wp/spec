<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum GoalMethod: string
{
    case FieldGoal = 'field_goal';
    case PenaltyThrow = 'penalty_throw';
    case OwnGoal = 'own_goal';

    public function label(): string
    {
        return match ($this) {
            self::FieldGoal => 'Field Goal',
            self::PenaltyThrow => 'Penalty Throw',
            self::OwnGoal => 'Own Goal',
        };
    }
}
