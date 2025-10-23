<?php

namespace App\Enums;

enum ClientType: string
{
    case REGULAR = 'regular';
    case PRIORITY = 'priority';
    case DEFERRED = 'deferred';

    public function prefix(): string
    {
        return match ($this) {
            self::REGULAR => 'R',
            self::PRIORITY => 'P',
            self::DEFERRED => 'D',
        };
    }
}
