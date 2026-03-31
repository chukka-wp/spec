<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum SubstitutionType: string
{
    case Flying = 'flying';
    case Standard = 'standard';
    case Bleeding = 'bleeding';

    public function label(): string
    {
        return match ($this) {
            self::Flying => 'Flying (during play)',
            self::Standard => 'Standard (during stoppage)',
            self::Bleeding => 'Bleeding (immediate)',
        };
    }
}
