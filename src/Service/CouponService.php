<?php

namespace App\Service;

use App\Entity\Coupon\Coupon;
use App\Enum\CouponType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class CouponService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    private function getType(string $couponCode): CouponType
    {
        return match (true) {
            str_contains($couponCode, CouponType::FIXED_DISCOUNT->value) => CouponType::FIXED_DISCOUNT,
            str_contains($couponCode, CouponType::PERCENT_DISCOUNT->value) => CouponType::PERCENT_DISCOUNT,
        };
    }

    /**
     * @throws Exception
     */
    private function getCouponValue(string $couponCode): int
    {
        $numericValue = str_replace(CouponType::getValues(), '', $couponCode);

        if (!is_numeric($numericValue)) {
            throw new Exception('Неверный формат купона');
        }

        return (int) $numericValue;
    }

    /**
     * @throws Exception
     */
    public function getPriceWithDiscount(?string $couponCode, int $price): float
    {
        if (is_null($couponCode)) {
            return $price;
        }

        //Получаем репозиторий нужного типа для поиска по дискриминатору
        $repository = $this->entityManager->getRepository(
            Coupon::COUPON_DISCRIMINATOR_MAP[$this->getType($couponCode)->value]
        );

        $price = $repository
            ->getByValue($this->getCouponValue($couponCode))
            ->calcDiscount($price);

        if (!($price > 0)) {
            throw new Exception('Что-то пошло не так. Цена должна быть больше нуля.');
        }

        return $price;
    }
}
