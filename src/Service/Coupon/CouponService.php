<?php

namespace App\Service\Coupon;

use App\Entity\Coupon\Coupon;
use App\Enum\CouponType;
use Exception;

class CouponService
{
    public function __construct(
        private readonly CouponProvider $couponProvider,
    ) {
    }

    public static function getType(string $couponCode): CouponType
    {
        return match (true) {
            str_contains($couponCode, CouponType::FIXED_DISCOUNT->value) => CouponType::FIXED_DISCOUNT,
            str_contains($couponCode, CouponType::PERCENT_DISCOUNT->value) => CouponType::PERCENT_DISCOUNT,
        };
    }

    /**
     * @throws Exception
     */
    public static function getCouponValue(string $couponCode): int
    {
        $numericValue = str_replace(CouponType::getValues(), '', $couponCode);

        return (int) $numericValue;
    }

    /**
     * @throws Exception
     */
    public function getPriceWithDiscount(?Coupon $coupon, int $price): float
    {
        if (is_null($coupon)) {
            return $price;
        }

        try {
            $couponCalculation = $this->couponProvider->handleCoupon($coupon->getType());
        } catch (Exception $exception) {
            throw new Exception('coupon_service.coupon_calculation.not_found');
        }

        $price = $couponCalculation->calcPrice(coupon: $coupon, price: $price);

        if (!($price > 0)) {
            throw new Exception('coupon_service.bad_price');
        }

        return $price;
    }
}
