<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum PenaltyOutcome: string
{
    case Goal = 'goal';
    case Miss = 'miss';
    case Saved = 'saved';
    case ReboundInPlay = 'rebound_in_play';

    public function label(): string
    {
        return match ($this) {
            self::Goal => 'Goal',
            self::Miss => 'Miss',
            self::Saved => 'Saved',
            self::ReboundInPlay => 'Rebound In Play',
        };
    }
}
