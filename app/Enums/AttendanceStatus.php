<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case Present = 'Present';
    case Absent = 'Absent';
    case Late = 'Late';

    public function getLabel(): string
    {
        return match ($this) {
            self::Present => 'Present',
            self::Absent => 'Absent',
            self::Late => 'Late',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Present => 'Member attended the class',
            self::Absent => 'Member did not attend',
            self::Late => 'Member arrived late',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Present => 'heroicon-o-check-circle',
            self::Absent => 'heroicon-o-x-circle',
            self::Late => 'heroicon-o-clock',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Present => 'success',
            self::Absent => 'danger',
            self::Late => 'warning',
        };
    }

    public function shouldDeductSession(): bool
    {
        return match ($this) {
            self::Present, self::Late => true,
            self::Absent => false,
        };
    }

    public function isPresent(): bool
    {
        return $this === self::Present;
    }

    public function isAbsent(): bool
    {
        return $this === self::Absent;
    }

    public function isLate(): bool
    {
        return $this === self::Late;
    }

    public static function getOptions(): array
    {
        return [
            self::Present->value => self::Present->getLabel(),
            self::Absent->value => self::Absent->getLabel(),
            self::Late->value => self::Late->getLabel(),
        ];
    }
}
