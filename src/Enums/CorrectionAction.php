<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum CorrectionAction: string
{
    case Void = 'void';
    case Replace = 'replace';

    public function label(): string
    {
        return match ($this) {
            self::Void => 'Void',
            self::Replace => 'Replace',
        };
    }
}
