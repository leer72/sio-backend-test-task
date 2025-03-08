<?php

namespace App\DTO;

readonly class CalculatePriceDTO
{
    public function __construct(
        public int    $productId,
        public string $taxNumber,
        public string $couponCode,
    ) {
    }
}
