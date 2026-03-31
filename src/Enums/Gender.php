<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum Gender: string
{
    case Male = 'male';
    case Female = 'female';
    case Mixed = 'mixed';

    public function label(): string
    {
        return match ($this) {
            self::Male => 'Male',
            self::Female => 'Female',
            self::Mixed => 'Mixed',
        };
    }
}
