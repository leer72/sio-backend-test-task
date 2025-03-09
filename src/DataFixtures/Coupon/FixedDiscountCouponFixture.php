<?php

namespace App\DataFixtures\Coupon;

use App\Entity\Coupon\FixedDiscountCoupon;
use Doctrine\Persistence\ObjectManager;

class FixedDiscountCouponFixture extends CouponFixture
{
    public function load(ObjectManager $manager): void
    {
        $coupon = new FixedDiscountCoupon(
            value: $this->value ?? $this->faker->numberBetween(10, 50),
        );

        $this->saveEntity($manager, $coupon);
    }
}
