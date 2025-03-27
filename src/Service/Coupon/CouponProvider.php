<?php

namespace App\Service\Coupon;

use App\Enum\CouponType;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class CouponProvider
{
    private iterable $coupons = [];

    public function __construct(
        #[TaggedIterator('app.coupon_calculation')] iterable $coupons
    ) {
        $this->coupons = $coupons;
    }

    /**
     * @throws Exception
     */
    public function handleCoupon(CouponType $needle): CouponCalculationInterface
    {
        /** @var CouponCalculationInterface $coupon */
        foreach ($this->coupons as $coupon) {
            if ($coupon->support($needle)) {
                return $coupon;
            }
        }

        throw new Exception();
    }
}
