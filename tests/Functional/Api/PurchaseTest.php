<?php

use App\Enum\CouponType;
use App\Service\TaxNumberService;
use App\Tests\Functional\AbstractFunctionalTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PurchaseTest extends AbstractFunctionalTest
{
    public const URL = '/purchase';
    public const string PRICE_FIELD_NAME = 'price';

    public const string DEFAULT_TAX_NUMBER = 'FRUU123456789';
    public const string BAD_COUPON = 'D10';
    public const string NO_VALID_COUPON = 'A20';
    public const string DEFAULT_PAYMENT_PROCESSOR = 'paypal';
    public const string STRIPE_PAYMENT_PROCESSOR = 'stripe';
    public const string BAD_PAYMENT_PROCESSOR = 'bad';
    public const int BAD_PRICE_FOR_PAYPAL = 120000;
    public const int BAD_PRICE_FOR_STRIPE = 50;

    private TaxNumberService $taxNumberService;

    /**
     * @dataProvider purchaseDataProvider
     */
    public function testPurchase(
        array $body,
        bool $isValid,
        ?int $discount = null,
        ?CouponType $couponType = null,
    ): void {
        $this->taxNumberService = new TaxNumberService();

        $product = $this->addProduct();

        if (isset($couponType)) {
            $this->addCoupon(value: $discount, type: $couponType);
        }

        $body['product'] = $product->getId();
        $body['couponCode'] = $couponType ? $couponType->value . $discount : null;

        $response = $this->sendRequest(
            method: Request::METHOD_POST,
            body: $body,
        );

        if ($isValid) {
            self::assertEquals(
                expected: Response::HTTP_OK,
                actual: $response->getStatusCode(),
            );

        } else {
            self::assertEquals(
                expected: Response::HTTP_UNPROCESSABLE_ENTITY,
                actual: $response->getStatusCode(),
            );
        }

    }

    public function testPurchaseNoValidProduct(): void
    {
        $body['product'] = $this->addProduct()->getId() + 1;
        $body['taxNumber'] = self::DEFAULT_TAX_NUMBER;
        $body['paymentProcessor'] = self::DEFAULT_PAYMENT_PROCESSOR;

        self::assertEquals(
            expected: Response::HTTP_BAD_REQUEST,
            actual: $this->sendRequest(
                method: Request::METHOD_POST,
                body: $body,
            )->getStatusCode(),
        );
    }

    public function testPurchaseNoValidCoupon(): void
    {
        $body['product'] = $this->addProduct()->getId();
        $body['couponCode'] = self::NO_VALID_COUPON;
        $body['taxNumber'] = self::DEFAULT_TAX_NUMBER;
        $body['paymentProcessor'] = self::DEFAULT_PAYMENT_PROCESSOR;

        self::assertEquals(
            expected: Response::HTTP_UNPROCESSABLE_ENTITY,
            actual: $this->sendRequest(
                method: Request::METHOD_POST,
                body: $body,
            )->getStatusCode(),
        );
    }

    public function testPurchaseBadCoupon(): void
    {
        $body['product'] = $this->addProduct()->getId();
        $body['couponCode'] = self::BAD_COUPON;
        $body['taxNumber'] = self::DEFAULT_TAX_NUMBER;
        $body['paymentProcessor'] = self::DEFAULT_PAYMENT_PROCESSOR;

        self::assertEquals(
            expected: Response::HTTP_BAD_REQUEST,
            actual: $this->sendRequest(
                method: Request::METHOD_POST,
                body: $body,
            )->getStatusCode(),
        );
    }

    public function testPurchaseBadPriceWithPaypal(): void
    {
        $body['product'] = $this->addProduct(price: self::BAD_PRICE_FOR_PAYPAL)->getId();
        $body['taxNumber'] = self::DEFAULT_TAX_NUMBER;
        $body['paymentProcessor'] = self::DEFAULT_PAYMENT_PROCESSOR;

        self::assertEquals(
            expected: Response::HTTP_BAD_REQUEST,
            actual: $this->sendRequest(
                method: Request::METHOD_POST,
                body: $body,
            )->getStatusCode(),
        );
    }

    public function testPurchaseBadPriceWithStripe(): void
    {
        $body['product'] = $this->addProduct(price: self::BAD_PRICE_FOR_STRIPE)->getId();
        $body['taxNumber'] = self::DEFAULT_TAX_NUMBER;
        $body['paymentProcessor'] = self::STRIPE_PAYMENT_PROCESSOR;

        self::assertEquals(
            expected: Response::HTTP_BAD_REQUEST,
            actual: $this->sendRequest(
                method: Request::METHOD_POST,
                body: $body,
            )->getStatusCode(),
        );
    }

    public function testPurchaseBadPaymentProcessor(): void
    {
        $body['product'] = $this->addProduct()->getId();
        $body['taxNumber'] = self::DEFAULT_TAX_NUMBER;
        $body['paymentProcessor'] = self::BAD_PAYMENT_PROCESSOR;

        self::assertEquals(
            expected: Response::HTTP_UNPROCESSABLE_ENTITY,
            actual: $this->sendRequest(
                method: Request::METHOD_POST,
                body: $body,
            )->getStatusCode(),
        );
    }

    public function purchaseDataProvider(): array
    {
        return [
            'valid_values_germany_tax_number' => [
                'body' => [
                    'taxNumber' => 'DE123456789',
                    'paymentProcessor' => 'paypal',
                ],
                'is_valid' => true,
                'discount' => 20,
                'coupon_type' => CouponType::FIXED_DISCOUNT,
            ],
            'valid_values_italy_tax_number' => [
                'body' => [
                    'taxNumber' => 'IT12345678900',
                    'paymentProcessor' => 'paypal',
                ],
                'is_valid' => true,
                'discount' => 20,
                'coupon_type' => CouponType::PERCENT_DISCOUNT,
            ],
            'valid_values_greece_tax_number' => [
                'body' => [
                    'taxNumber' => 'GR123456789',
                    'paymentProcessor' => 'paypal',
                ],
                'is_valid' => true,
                'discount' => 15,
                'coupon_type' => CouponType::FIXED_DISCOUNT,
            ],
            'valid_values_france_tax_number' => [
                'body' => [
                    'taxNumber' => 'FRUU123456789',
                    'paymentProcessor' => 'paypal',
                ],
                'is_valid' => true,
                'discount' => 50,
                'coupon_type' => CouponType::PERCENT_DISCOUNT,
            ],
            'valid_values_without_coupon' => [
                'body' => [
                    'taxNumber' => 'FRUU123456789',
                    'paymentProcessor' => 'paypal',
                ],
                'is_valid' => true,
            ],
            'no_valid_values_tax_number' => [
                'body' => [
                    'taxNumber' => 'FR99123456789',
                    'paymentProcessor' => 'paypal',
                ],
                'is_valid' => false,
                'discount' => 50,
                'coupon_type' => CouponType::PERCENT_DISCOUNT,
            ],
        ];
    }
}
