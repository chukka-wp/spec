<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum PossessionChangeReason: string
{
    case GoalScored = 'goal_scored';
    case FreeThrow = 'free_throw';
    case Turnover = 'turnover';
    case ExclusionExpiry = 'exclusion_expiry';
    case TimeoutEnd = 'timeout_end';
    case PeriodStart = 'period_start';

    public function label(): string
    {
        return match ($this) {
            self::GoalScored => 'Goal Scored',
            self::FreeThrow => 'Free Throw',
            self::Turnover => 'Turnover',
            self::ExclusionExpiry => 'Exclusion Expiry',
            self::TimeoutEnd => 'Timeout End',
            self::PeriodStart => 'Period Start',
        };
    }
}
