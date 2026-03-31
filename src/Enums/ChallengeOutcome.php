<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum ChallengeOutcome: string
{
    case Successful = 'successful';
    case Unsuccessful = 'unsuccessful';

    public function label(): string
    {
        return match ($this) {
            self::Successful => 'Successful (challenge retained)',
            self::Unsuccessful => 'Unsuccessful (challenge consumed)',
        };
    }
}
