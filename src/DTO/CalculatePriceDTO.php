<?php

namespace App\DTO;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

readonly class CalculatePriceDTO
{
    public function __construct(
        #[Assert\Type(
            type: Types::INTEGER,
            message: 'dto.product.type'
        )]
        #[Assert\Positive(message: 'dto.product.positive')]
        public int $product,

        #[Assert\NotBlank(message: 'dto.tax_number.not_blank')]
        #[Assert\Regex(
            pattern: '/^(DE\d{9}|IT\d{11}|GR\d{9}|FR[A-Z]{2}\d{9})$/',
            message: 'dto.tax_number.regex',
        )]
        public string $taxNumber,

        #[Assert\Type(
            type: Types::STRING,
            message: 'dto.coupon_code.type'
        )]
        #[AcmeAssert\CouponCode]
        public ?string $couponCode,
    ) {
    }
}
