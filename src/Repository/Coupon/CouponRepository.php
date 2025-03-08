<?php

namespace App\Repository\Coupon;

use App\Entity\Coupon;
use App\Enum\CouponType;
use App\Repository\AbstractRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class CouponRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coupon::class);
    }

    public function getByValueAndType(string $value, CouponType $type)
    {
        $coupon = $this->findOneBy(['value' => $value, 'discount_type' => $type]);

        if (is_null($coupon)) {
            throw new EntityNotFoundException(
                message: 'Coupon not found',
            );
        }

        return $coupon;
    }
}
