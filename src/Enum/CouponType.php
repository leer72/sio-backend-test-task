<?php

namespace App\Enum;

enum CouponType: int
{
    case FIXED_DISCOUNT = 1;

    case PERCENT_DISCOUNT = 2;

    public const array VALID_VALUES = [
        self::FIXED_DISCOUNT,
        self::PERCENT_DISCOUNT,
    ];
}
