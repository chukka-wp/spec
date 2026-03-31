<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum CornerThrowSide: string
{
    case Left = 'left';
    case Right = 'right';

    public function label(): string
    {
        return match ($this) {
            self::Left => 'Left',
            self::Right => 'Right',
        };
    }
}
