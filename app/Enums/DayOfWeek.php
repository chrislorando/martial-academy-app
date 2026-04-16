<?php

namespace App\Enums;

enum DayOfWeek: string
{
    case Monday = 'Monday';
    case Tuesday = 'Tuesday';
    case Wednesday = 'Wednesday';
    case Thursday = 'Thursday';
    case Friday = 'Friday';
    case Saturday = 'Saturday';
    case Sunday = 'Sunday';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getShortLabel(): string
    {
        return match ($this) {
            self::Monday => 'Mon',
            self::Tuesday => 'Tue',
            self::Wednesday => 'Wed',
            self::Thursday => 'Thu',
            self::Friday => 'Fri',
            self::Saturday => 'Sat',
            self::Sunday => 'Sun',
        };
    }

    public function getNumericValue(): int
    {
        return match ($this) {
            self::Monday => 1,
            self::Tuesday => 2,
            self::Wednesday => 3,
            self::Thursday => 4,
            self::Friday => 5,
            self::Saturday => 6,
            self::Sunday => 7,
        };
    }

    public function isWeekday(): bool
    {
        return match ($this) {
            self::Saturday, self::Sunday => false,
            default => true,
        };
    }

    public function isWeekend(): bool
    {
        return !$this->isWeekday();
    }

    public static function all(): array
    {
        return [
            self::Monday,
            self::Tuesday,
            self::Wednesday,
            self::Thursday,
            self::Friday,
            self::Saturday,
            self::Sunday,
        ];
    }

    public static function getOptions(): array
    {
        return [
            self::Monday->value => self::Monday->getLabel(),
            self::Tuesday->value => self::Tuesday->getLabel(),
            self::Wednesday->value => self::Wednesday->getLabel(),
            self::Thursday->value => self::Thursday->getLabel(),
            self::Friday->value => self::Friday->getLabel(),
            self::Saturday->value => self::Saturday->getLabel(),
            self::Sunday->value => self::Sunday->getLabel(),
        ];
    }
}
