<?php

namespace App\Tests\Functional;

use App\DataFixtures\Coupon\CouponFixture;
use App\DataFixtures\Coupon\FixedDiscountCouponFixture;
use App\DataFixtures\Coupon\PercentDiscountCouponFixture;
use App\DataFixtures\Product\ProductFixture;
use App\Entity\Coupon\Coupon;
use App\Entity\Product;
use App\Enum\CouponType;
use App\Tests\AbstractTest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManager;
use JsonException;
use Symfony\Component\HttpFoundation\Response;

class AbstractFunctionalTest extends AbstractTest
{
    private function loadFixture(Fixture $fixture): void
    {
        $container = $this->client->getContainer();

        $em = $container->get('doctrine.orm.entity_manager');

        $fixture->load($em);

        $container->set(
            EntityManager::class,
            $em,
        );
    }

    protected function addProduct(
        ?string $name = null,
        ?int $price = null,
    ): Product {
        $product = new ProductFixture();

        $product = is_null($name) ? $product : $product->setName($name);
        $product = is_null($price) ? $product : $product->setPrice($price);

        $this->loadFixture($product);

        return $product->getProduct();
    }

    protected function addCoupon(
        ?int $value = null,
        ?CouponType $type = null,
    ): Coupon {
        $coupon = new CouponFixture();

        $coupon = is_null($value) ? $coupon : $coupon->setValue($value);
        $coupon = is_null($type) ? $coupon : $coupon->setType($type);

        $this->loadFixture($coupon);

        return $coupon->getCoupon();
    }

    /**
     * @throws JsonException
     */
    protected function getJsonResponse(Response $response): array
    {
        return json_decode(
            json: $response->getContent(),
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        );
    }
}
