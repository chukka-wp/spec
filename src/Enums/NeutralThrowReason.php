<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum NeutralThrowReason: string
{
    case SimultaneousFoul = 'simultaneous_foul';
    case SimultaneousWhistle = 'simultaneous_whistle';
    case BallInObstruction = 'ball_in_obstruction';
    case DisputedStart = 'disputed_start';

    public function label(): string
    {
        return match ($this) {
            self::SimultaneousFoul => 'Simultaneous Foul',
            self::SimultaneousWhistle => 'Simultaneous Whistle',
            self::BallInObstruction => 'Ball In Obstruction',
            self::DisputedStart => 'Disputed Start',
        };
    }
}
