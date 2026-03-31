<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum CardRecipient: string
{
    case HeadCoach = 'head_coach';
    case TeamOfficial = 'team_official';
    case Player = 'player';

    public function label(): string
    {
        return match ($this) {
            self::HeadCoach => 'Head Coach',
            self::TeamOfficial => 'Team Official',
            self::Player => 'Player',
        };
    }
}
