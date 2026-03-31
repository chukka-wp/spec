<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum CapNumberScheme: string
{
    case Sequential = 'sequential';
    case Open = 'open';

    public function label(): string
    {
        return match ($this) {
            self::Sequential => 'Sequential (1–14)',
            self::Open => 'Open (any two-digit)',
        };
    }
}
