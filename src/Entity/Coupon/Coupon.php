<?php

namespace App\Entity\Coupon;

use App\Enum\CouponType;
use App\Repository\Coupon\CouponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

define('COUPON_DISCRIMINATOR', [
    CouponType::PERCENT_DISCOUNT->value => PercentDiscountCoupon::class,
    CouponType::FIXED_DISCOUNT->value => FixedDiscountCoupon::class,
]);

#[ORM\Entity(repositoryClass: CouponRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discount_type', type: Types::STRING)]
#[ORM\DiscriminatorMap(COUPON_DISCRIMINATOR)]
abstract class Coupon
{
    public const array COUPON_DISCRIMINATOR_MAP = COUPON_DISCRIMINATOR;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int $id;

    #[ORM\Column]
    protected int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function setValue(int $value): Coupon
    {
        $this->value = $value;

        return $this;
    }

    abstract public function calcDiscount(int $price): float;
}
