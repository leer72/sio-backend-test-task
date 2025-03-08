<?php

namespace App\Entity\Coupon;

use App\Repository\Coupon\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
#[ORM\Table]
class PercentDiscountCoupon extends Coupon
{
    public function calcDiscount(int $price): float
    {
        if ($this->getValue() >=100) {
            return 0.0;
        }

        return $price - $price * $this->getValue() / 100;
    }
}
