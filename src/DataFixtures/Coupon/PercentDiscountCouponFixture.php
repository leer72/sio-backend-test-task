<?php

namespace App\DataFixtures\Coupon;

use App\Entity\Coupon\PercentDiscountCoupon;
use Doctrine\Persistence\ObjectManager;

class PercentDiscountCouponFixture extends CouponFixture
{
    public function load(ObjectManager $manager): void
    {
        $coupon = new PercentDiscountCoupon(
            value: $this->value ?? $this->faker->numberBetween(10, 20),
        );

        $this->saveEntity($manager, $coupon);
    }
}
