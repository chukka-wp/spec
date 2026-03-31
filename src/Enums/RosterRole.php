<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum RosterRole: string
{
    case FieldPlayer = 'field_player';
    case Goalkeeper = 'goalkeeper';
    case SubstituteGoalkeeper = 'substitute_goalkeeper';

    public function label(): string
    {
        return match ($this) {
            self::FieldPlayer => 'Field Player',
            self::Goalkeeper => 'Goalkeeper',
            self::SubstituteGoalkeeper => 'Substitute Goalkeeper',
        };
    }
}
