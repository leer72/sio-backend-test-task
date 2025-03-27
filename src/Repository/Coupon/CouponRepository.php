<?php

namespace App\Repository\Coupon;

use App\Entity\Coupon\Coupon;
use App\Enum\CouponType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\TranslatorInterface;

class CouponRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Coupon::class);
    }

    public function getByValueAndType(int $value, CouponType $couponType): Coupon
    {
        $coupon = $this->findOneBy(['value' => $value, 'type' => $couponType]);

        if (is_null($coupon)) {
            throw new EntityNotFoundException(
                message: 'coupon_not_found',
            );
        }

        return $coupon;
    }
}
