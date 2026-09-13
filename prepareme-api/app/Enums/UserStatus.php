<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case BLOCKED = 'blocked';

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }
}
