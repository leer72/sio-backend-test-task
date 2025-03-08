<?php

namespace App\Facade;

use App\Repository\Product\ProductRepository;
use App\Service\CouponService;
use App\Service\TaxNumberService;

class CalculationFacade
{
    public function __construct(
        private readonly CouponService $couponService,
        private readonly TaxNumberService $taxNumberService,
        private readonly ProductRepository $productRepository,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function getFinalProductPrice(
        int $productId,
        string $couponCode,
        string $taxNumber,
    ): float {
        return $this->taxNumberService->getPriceWithTax(
            taxNumber: $taxNumber,
            price: $this->couponService->getPriceWithDiscount(
                couponCode:  $couponCode,
                price: $this->productRepository->getById(id: $productId)->getPrice(),
            )
        );
    }
}
