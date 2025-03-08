<?php

namespace App\Enum;

enum Countries: string
{
    case GERMANY = 'DE';

    case ITALY = 'IT';

    case FRANCE = 'FR';

    case GREECE = 'GR';

    public const array VALID_VALUES = [
        self::GERMANY,
        self::ITALY,
        self::FRANCE,
        self::GREECE,
    ];
}
