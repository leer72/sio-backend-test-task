<?php

namespace App\Service\Payment;

use App\Exception\PaymentException;
use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PaypalAdapter implements PaymentInterface
{
    private PaypalPaymentProcessor $paymentProcessor;

    public function __construct()
    {
        $this->paymentProcessor = new PaypalPaymentProcessor();
    }

    /**
     * @throws PaymentException
     */
    public function processPayment(float $price): void
    {
        try {
            $this->paymentProcessor->pay((int) round($price));
        } catch (Exception $exception) {
            throw new PaymentException($exception->getMessage());
        }

    }
}
