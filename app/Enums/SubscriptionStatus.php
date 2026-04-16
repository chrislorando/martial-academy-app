<?php

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Active = 'Active';
    case Expired = 'Expired';
    case Frozen = 'Frozen';
    case Completed = 'Completed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Expired => 'Expired',
            self::Frozen => 'Frozen',
            self::Completed => 'Completed',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Active => 'Subscription is currently active',
            self::Expired => 'Subscription has expired',
            self::Frozen => 'Subscription is temporarily frozen',
            self::Completed => 'All sessions have been used',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Active => 'heroicon-o-check-circle',
            self::Expired => 'heroicon-o-x-circle',
            self::Frozen => 'heroicon-o-pause-circle',
            self::Completed => 'heroicon-o-check-badge',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Expired => 'danger',
            self::Frozen => 'warning',
            self::Completed => 'gray',
        };
    }

    public function isActive(): bool
    {
        return $this === self::Active;
    }

    public function isExpired(): bool
    {
        return $this === self::Expired;
    }

    public function isFrozen(): bool
    {
        return $this === self::Frozen;
    }

    public function isCompleted(): bool
    {
        return $this === self::Completed;
    }

    public function canBeUsed(): bool
    {
        return $this === self::Active;
    }
}
