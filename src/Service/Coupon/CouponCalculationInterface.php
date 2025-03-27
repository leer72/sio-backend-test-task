<?php

namespace App\Service\Coupon;

use App\Entity\Coupon\Coupon;
use App\Enum\CouponType;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: "app.coupon_calculation")]
interface CouponCalculationInterface
{
    public function calcPrice(Coupon $coupon, int $price): float;

    public function support(CouponType $couponType): bool;
}
