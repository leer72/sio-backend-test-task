<?php

namespace App\Entity\Coupon;

use App\Enum\CouponType;
use App\Repository\Coupon\CouponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int $id;

    #[ORM\Column]
    protected int $value;

    #[ORM\Column(type: Types::STRING, enumType: CouponType::class)]
    private CouponType $type;

    public function __construct(
        int $value,
        CouponType $type,
    ) {
        $this->value = $value;
        $this->type = $type;
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

    public function getType(): CouponType
    {
      return $this->type;
    }

    public function setType(CouponType $type): Coupon
    {
      $this->type = $type;

      return $this;
    }
}
