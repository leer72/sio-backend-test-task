<?php

namespace App\Service\Coupon;

use App\Entity\Coupon\Coupon;
use App\Enum\CouponType;

class FixedDiscountCoupon implements CouponCalculationInterface
{
    public function calcPrice(Coupon $coupon, int $price): float
    {
        if ($price <= $coupon->getValue()) {
            return 0;
        }

        return $price - $coupon->getValue();
    }

    public function support(CouponType $couponType): bool
    {
        return CouponType::FIXED_DISCOUNT === $couponType;
    }
}
