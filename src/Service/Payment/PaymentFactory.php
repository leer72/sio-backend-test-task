<?php

namespace App\Service\Payment;

use App\Exception\PaymentException;

class PaymentFactory
{
    private const array PAYMENT_MAP = [
        'paypal' => PaypalAdapter::class,
        'stripe' => StripeAdapter::class,
    ];

    /**
     * @throws PaymentException
     */
    public static function getPaymentProcessor(string $paymentType): PaymentInterface
    {
        if (!array_key_exists($paymentType, self::PAYMENT_MAP)) {
            throw new PaymentException("Тип платежного процессора '{$paymentType}' не поддерживается");
        }

        $paymentClass = self::PAYMENT_MAP[$paymentType];

        return new $paymentClass;
    }
}
