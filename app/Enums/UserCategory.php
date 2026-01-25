<?php

namespace App\Enums;

enum UserCategory: string
{
    case REGULAR = 'regular';
    case PRIORITY = 'priority';
    case BOTH = 'both';

    public function description(): string
    {
        return match ($this) {
            self::REGULAR => 'Regular',
            self::PRIORITY => 'Priority',
            self::BOTH => 'Both',
        };
    }
}
