<?php

namespace App\Repository\Coupon;

use App\Entity\Coupon\Coupon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

class CouponRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coupon::class);
    }

    public function getByValue(int $value): Coupon
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
