<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum PossessionClockResetReason: string
{
    case NewPossession = 'new_possession';
    case ShotReboundAttacking = 'shot_rebound_attacking';
    case ExclusionFoul = 'exclusion_foul';
    case TwoMeterThrow = 'two_meter_throw';
    case PenaltyThrowNoChange = 'penalty_throw_no_change';
    case NeutralThrow = 'neutral_throw';
    case GoalThrow = 'goal_throw';
    case TimeoutEnd = 'timeout_end';

    public function label(): string
    {
        return match ($this) {
            self::NewPossession => 'New Possession',
            self::ShotReboundAttacking => 'Shot Rebound (Attacking)',
            self::ExclusionFoul => 'Exclusion Foul',
            self::TwoMeterThrow => 'Two-Meter Throw',
            self::PenaltyThrowNoChange => 'Penalty Throw (No Change)',
            self::NeutralThrow => 'Neutral Throw',
            self::GoalThrow => 'Goal Throw',
            self::TimeoutEnd => 'Timeout End',
        };
    }
}
