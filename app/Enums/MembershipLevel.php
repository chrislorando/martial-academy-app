<?php

namespace App\Enums;

enum MembershipLevel: string
{
    case WhiteBelt = 'White Belt';
    case YellowBelt = 'Yellow Belt';
    case OrangeBelt = 'Orange Belt';
    case GreenBelt = 'Green Belt';
    case BlueBelt = 'Blue Belt';
    case BrownBelt = 'Brown Belt';
    case BlackBelt = 'Black Belt';

    case Beginner = 'Beginner';
    case Intermediate = 'Intermediate';
    case Advanced = 'Advanced';

    public function getLabel(): string
    {
        return $this->value;
    }

    public function getRank(): int
    {
        return match ($this) {
            self::WhiteBelt => 1,
            self::YellowBelt => 2,
            self::OrangeBelt => 3,
            self::GreenBelt => 4,
            self::BlueBelt => 5,
            self::BrownBelt => 6,
            self::BlackBelt => 7,
            self::Beginner => 1,
            self::Intermediate => 2,
            self::Advanced => 3,
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::WhiteBelt, self::Beginner => 'gray',
            self::YellowBelt => 'warning',
            self::OrangeBelt => 'orange',
            self::GreenBelt => 'success',
            self::BlueBelt => 'info',
            self::BrownBelt, self::Intermediate => 'brown',
            self::BlackBelt, self::Advanced => 'danger',
        };
    }

    public function isBelt(): bool
    {
        return str_ends_with($this->value, 'Belt');
    }

    public function isGeneralLevel(): bool
    {
        return !$this->isBelt();
    }
}
