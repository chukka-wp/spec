<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum Possession: string
{
    case Home = 'home';
    case Away = 'away';
    case None = 'none';

    public function label(): string
    {
        return match ($this) {
            self::Home => 'Home',
            self::Away => 'Away',
            self::None => 'None',
        };
    }

    public function opposite(): self
    {
        return match ($this) {
            self::Home => self::Away,
            self::Away => self::Home,
            self::None => self::None,
        };
    }
}
