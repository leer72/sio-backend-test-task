<?php

namespace App\Entity;

use App\Enum\CouponType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $value;

    #[ORM\Column(name: 'discount_type', type: Types::INTEGER, enumType: CouponType::class)]
    private CouponType $discountType;

    /**
     * @param int $id
     * @param int $value
     * @param CouponType $discountType
     */
    public function __construct(int $value, CouponType $discountType)
    {
        $this->value = $value;
        $this->discountType = $discountType;
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

    public function getDiscountType(): CouponType
    {
        return $this->discountType;
    }

    public function setDiscountType(CouponType $discountType): Coupon
    {
        $this->discountType = $discountType;

        return $this;
    }
}
