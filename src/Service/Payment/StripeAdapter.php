<?php

namespace App\Service\Payment;

use App\Enum\PaymentProcessors;
use App\Exception\PaymentException;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripeAdapter implements PaymentInterface
{
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
            throw new PaymentException();
        }
    }

    public function support(PaymentProcessors $paymentProcessor): bool
    {
        return $paymentProcessor === PaymentProcessors::STRIPE;
    }
}
