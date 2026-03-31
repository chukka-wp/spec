<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum GameStatus: string
{
    case NotStarted = 'not_started';
    case InProgress = 'in_progress';
    case PeriodBreak = 'period_break';
    case Halftime = 'halftime';
    case Overtime = 'overtime';
    case Shootout = 'shootout';
    case Completed = 'completed';
    case Abandoned = 'abandoned';

    public function label(): string
    {
        return match ($this) {
            self::NotStarted => 'Not Started',
            self::InProgress => 'In Progress',
            self::PeriodBreak => 'Period Break',
            self::Halftime => 'Halftime',
            self::Overtime => 'Overtime',
            self::Shootout => 'Shootout',
            self::Completed => 'Completed',
            self::Abandoned => 'Abandoned',
        };
    }
}
