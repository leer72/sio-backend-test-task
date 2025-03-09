<?php

namespace App\DataFixtures\Coupon;

use App\DataFixtures\AbstractFixture;
use App\Entity\Coupon\Coupon;
use Doctrine\Persistence\ObjectManager;

abstract class CouponFixture extends AbstractFixture
{
    protected int $value;

    protected Coupon $coupon;

    public function setValue(int $value): self
    {
        $this->value = $value;

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
