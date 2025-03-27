<?php

namespace App\DataFixtures\Coupon;

use App\DataFixtures\AbstractFixture;
use App\Entity\Coupon\Coupon;
use App\Enum\CouponType;
use Doctrine\Persistence\ObjectManager;

class CouponFixture extends AbstractFixture
{
    protected int $value;

    protected CouponType $type;

    protected Coupon $coupon;

    public function load(ObjectManager $manager): void
    {
        $coupon = new Coupon(
            value: $this->value ?? $this->faker->numberBetween(10, 50),
            type: $this->type ?? $this->faker->randomElement(CouponType::getValues()),
        );

        $this->saveEntity($manager, $coupon);
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function setType(CouponType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCoupon(): Coupon
    {
        return $this->coupon;
    }

    protected function saveEntity(ObjectManager $manager, Coupon $entity): void
    {
        $manager->persist($entity);
        $manager->flush();

        $this->coupon = $entity;
    }
}
