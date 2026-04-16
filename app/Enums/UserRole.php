<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'Admin';
    case Coach = 'Coach';
    case Reception = 'Reception';

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Coach => 'Coach',
            self::Reception => 'Reception',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Admin => 'heroicon-o-shield-check',
            self::Coach => 'heroicon-o-academic-cap',
            self::Reception => 'heroicon-o-user-circle',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Coach => 'warning',
            self::Reception => 'success',
        };
    }
}
