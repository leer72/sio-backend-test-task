<?php

namespace App\Repository\Coupon;

use App\Entity\Coupon\Coupon;
use App\Repository\AbstractRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class CouponRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coupon::class);
    }

    public function getByValue(int $value)
    {
        $coupon = $this->findOneBy(['value' => $value]);

        if (is_null($coupon)) {
            throw new EntityNotFoundException(
                message: 'Купон не найден',
            );
        }

        return $coupon;
    }
}
