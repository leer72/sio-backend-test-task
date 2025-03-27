<?php

namespace App\Facade;

use App\Entity\Coupon\Coupon;
use App\Service\Coupon\CouponService;
use App\Service\TaxNumberService;
use Exception;

class CalculationFacade
{
    public function __construct(
        private readonly CouponService $couponService,
        private readonly TaxNumberService $taxNumberService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function getFinalProductPrice(
        int $productPrice,
        ?Coupon $coupon,
        string $taxNumber,
    ): float {
        return $this->taxNumberService->getPriceWithTax(
            taxNumber: $taxNumber,
            price: $this->couponService->getPriceWithDiscount(
                coupon: $coupon,
                price: $productPrice,
            )
        );
    }
}
