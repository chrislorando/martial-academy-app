<?php

namespace App\Enums;

enum SubscriptionType: string
{
    case Monthly = 'Monthly';
    case SessionBased = 'Session Based';

    public function getLabel(): string
    {
        return match ($this) {
            self::Monthly => 'Monthly',
            self::SessionBased => 'Session Based',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Monthly => 'Unlimited access for 30 days',
            self::SessionBased => 'Fixed number of sessions',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Monthly => 'heroicon-o-calendar',
            self::SessionBased => 'heroicon-o-numbered-list',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Monthly => 'primary',
            self::SessionBased => 'success',
        };
    }

    public function getDefaultValue(): int
    {
        return match ($this) {
            self::Monthly => 30, // days
            self::SessionBased => 10, // sessions (default)
        };
    }

    public function getDurationDays(): int
    {
        return match ($this) {
            self::Monthly => 30,
            self::SessionBased => 365, // 1 year validity for session-based
        };
    }

    public function isMonthly(): bool
    {
        return $this === self::Monthly;
    }

    public function isSessionBased(): bool
    {
        return $this === self::SessionBased;
    }
}
