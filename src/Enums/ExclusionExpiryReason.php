<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum ExclusionExpiryReason: string
{
    case TimeElapsed = 'time_elapsed';
    case GoalScored = 'goal_scored';
    case TeamRegainedPossession = 'team_regained_possession';
    case FreeThrowAwarded = 'free_throw_awarded';

    public function label(): string
    {
        return match ($this) {
            self::TimeElapsed => 'Time Elapsed',
            self::GoalScored => 'Goal Scored',
            self::TeamRegainedPossession => 'Team Regained Possession',
            self::FreeThrowAwarded => 'Free Throw Awarded',
        };
    }
}
