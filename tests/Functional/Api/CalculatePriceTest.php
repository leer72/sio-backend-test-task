<?php

namespace App\Tests\Functional\Api;

use App\Enum\CouponType;
use App\Service\TaxNumberService;
use App\Tests\Functional\AbstractFunctionalTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CalculatePriceTest extends AbstractFunctionalTest
{
    public const URL = '/calculate-price';
    public const string PRICE_FIELD_NAME = 'price';

    public const string DEFAULT_TAX_NUMBER = 'FRUU123456789';
    public const string BAD_COUPON = 'D10';
    public const string NO_VALID_COUPON = 'A20';

    private TaxNumberService $taxNumberService;

  /**
   * @dataProvider calculatePriceDataProvider
   */
    public function testCalculatePrice(
        array $body,
        bool $isValid,
        ?int $discount = null,
        ?CouponType $couponType = null,
    ): void {
        $this->taxNumberService = new TaxNumberService();

        $product = $this->addProduct();
        $coupon = null;

        if (isset($couponType)) {
            $coupon = $couponType === CouponType::FIXED_DISCOUNT
                ? $this->addFixedDiscountCoupon(value: $discount)
                : $this->addPercentDiscountCoupon(value: $discount);
        }

        $body['product'] = $product->getId();
        $body['couponCode'] = $couponType ? $couponType->value . $discount : null;

        $response = $this->sendRequest(
            method: Request::METHOD_POST,
            body: $body,
        );

        if ($isValid) {
            $responseJson = $this->getJsonResponse(
                response: $response,
            );

            self::assertEquals(
                expected: Response::HTTP_OK,
                actual: $response->getStatusCode(),
            );

            self::assertEquals(
                expected: $responseJson[self::PRICE_FIELD_NAME],
                actual: $this->taxNumberService->getPriceWithTax(
                    taxNumber: $body['taxNumber'],
                    price: $coupon
                        ? $coupon->calcDiscount(price: $product->getPrice())
                        : $product->getPrice(),
                ),
            );
        } else {
            self::assertEquals(
                expected: Response::HTTP_BAD_REQUEST,
                actual: $response->getStatusCode(),
            );
        }

    }

    public function testCalculatePriceNoValidProduct(): void
    {
        $body['product'] = $this->addProduct()->getId() + 1;
        $body['taxNumber'] = self::DEFAULT_TAX_NUMBER;

        self::assertEquals(
            expected: Response::HTTP_BAD_REQUEST,
            actual: $this->sendRequest(
                method: Request::METHOD_POST,
                body: $body,
            )->getStatusCode(),
        );
    }

    public function testCalculatePriceNoValidCoupon(): void
    {
        $body['product'] = $this->addProduct()->getId();
        $body['couponCode'] = self::NO_VALID_COUPON;
        $body['taxNumber'] = self::DEFAULT_TAX_NUMBER;

        self::assertEquals(
            expected: Response::HTTP_BAD_REQUEST,
            actual: $this->sendRequest(
                method: Request::METHOD_POST,
                body: $body,
            )->getStatusCode(),
        );
    }

    public function testCalculatePriceBadCoupon(): void
    {
        $body['product'] = $this->addProduct()->getId();
        $body['couponCode'] = self::BAD_COUPON;
        $body['taxNumber'] = self::DEFAULT_TAX_NUMBER;

        self::assertEquals(
            expected: Response::HTTP_BAD_REQUEST,
            actual: $this->sendRequest(
                method: Request::METHOD_POST,
                body: $body,
            )->getStatusCode(),
        );
    }

    public function calculatePriceDataProvider(): array
    {
        return [
            'valid_values_germany_tax_number' => [
                'body' => [
                    'taxNumber' => 'DE123456789',
                ],
                'is_valid' => true,
                'discount' => 20,
                'coupon_type' => CouponType::FIXED_DISCOUNT,
            ],
            'valid_values_italy_tax_number' => [
                'body' => [
                    'taxNumber' => 'IT12345678900',
                ],
                'is_valid' => true,
                'discount' => 20,
                'coupon_type' => CouponType::PERCENT_DISCOUNT,
            ],
            'valid_values_greece_tax_number' => [
                'body' => [
                    'taxNumber' => 'GR123456789',
                ],
                'is_valid' => true,
                'discount' => 15,
                'coupon_type' => CouponType::FIXED_DISCOUNT,
            ],
            'valid_values_france_tax_number' => [
                'body' => [
                    'taxNumber' => 'FRUU123456789',
                ],
                'is_valid' => true,
                'discount' => 50,
                'coupon_type' => CouponType::PERCENT_DISCOUNT,
            ],
            'valid_values_without_coupon' => [
                'body' => [
                    'taxNumber' => 'FRUU123456789',
                ],
                'is_valid' => true,
            ],
            'no_valid_values_tax_number' => [
                'body' => [
                    'taxNumber' => 'FR99123456789',
                ],
                'is_valid' => false,
                'discount' => 50,
                'coupon_type' => CouponType::PERCENT_DISCOUNT,
            ],
        ];
    }
}
