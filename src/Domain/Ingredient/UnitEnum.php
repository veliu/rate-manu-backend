<?php

namespace Veliu\RateManu\Domain\Ingredient;

enum UnitEnum: string
{
    case GRAM = 'g';
    case KILOGRAM = 'kg';
    case LITER = 'l';
    case MILLILITER = 'ml';
    case PIECE = 'pcs';

    public const array VALUES = ['g', 'kg', 'l', 'ml', 'pcs'];

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
