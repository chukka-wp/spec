<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum ExclusionType: string
{
    case Standard = 'standard';
    case ViolentAction = 'violent_action';
    case ForGame = 'for_game';

    public function label(): string
    {
        return match ($this) {
            self::Standard => 'Standard (20s)',
            self::ViolentAction => 'Violent Action (4 min sub delay)',
            self::ForGame => 'Excluded for Game',
        };
    }
}
