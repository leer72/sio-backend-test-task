<?php

namespace App\DTO;

readonly class PurchaseDTO
{
    public function __construct(
        public int    $productId,
        public string $taxNumber,
        public string $couponCode,
        public string $paymentProcessor,
    ) {
    }
}
