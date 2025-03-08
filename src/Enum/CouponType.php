<?php

namespace App\Enum;

enum CouponType: string
{
    case FIXED_DISCOUNT = 'D';

    case PERCENT_DISCOUNT = 'P';

    public static function getValues(): array
    {
        return array_map(
            fn (CouponType $couponType) => $couponType->value,
            self::cases(),
        );
    }
}
