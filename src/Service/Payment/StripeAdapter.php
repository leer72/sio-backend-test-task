<?php

namespace App\Service\Payment;

use App\Exception\PaymentException;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripeAdapter implements PaymentInterface
{
    const string ERROR_MESSAGE = 'Не удалось совершить оплату';

    private StripePaymentProcessor $stripePaymentProcessor;

    public function __construct() {
        $this->stripePaymentProcessor = new StripePaymentProcessor();
    }

    /**
     * @throws PaymentException
     */
    public function processPayment(float $price): void
    {
        if (!$this->stripePaymentProcessor->processPayment($price)) {
            throw new PaymentException(self::ERROR_MESSAGE);
        }
    }
}
