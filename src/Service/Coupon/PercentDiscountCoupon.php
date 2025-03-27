<?php

namespace App\Service\Coupon;

use App\Entity\Coupon\Coupon;
use App\Enum\CouponType;

class PercentDiscountCoupon implements CouponCalculationInterface
{
    public function calcPrice(Coupon $coupon, int $price): float
    {
        if ($coupon->getValue() >= 100) {
            return 0;
        }

        return $price - $price * $coupon->getValue() / 100;
    }

    public function support(CouponType $couponType): bool
    {
        return CouponType::PERCENT_DISCOUNT === $couponType;
    }
}
