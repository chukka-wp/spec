<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum PossessionClockMode: string
{
    case Standard = 'standard';
    case Reduced = 'reduced';

    public function label(): string
    {
        return match ($this) {
            self::Standard => 'Standard',
            self::Reduced => 'Reduced',
        };
    }
}
