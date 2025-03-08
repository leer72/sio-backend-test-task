<?php

namespace App\Entity\Coupon;

use App\Repository\Coupon\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
#[ORM\Table]
class FixedDiscountCoupon extends Coupon
{
    public function calcDiscount(int $price): float
    {
        if ($price - $this->getValue()) {
            return (float) ($price - $this->getValue());
        }

        return 0.0;
    }
}
