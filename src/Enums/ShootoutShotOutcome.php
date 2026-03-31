<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum ShootoutShotOutcome: string
{
    case Goal = 'goal';
    case Miss = 'miss';
    case Saved = 'saved';

    public function label(): string
    {
        return match ($this) {
            self::Goal => 'Goal',
            self::Miss => 'Miss',
            self::Saved => 'Saved',
        };
    }
}
