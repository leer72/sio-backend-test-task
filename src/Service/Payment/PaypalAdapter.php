<?php

namespace App\Service\Payment;

use App\Enum\PaymentProcessors;
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
            $this->paymentProcessor->pay(round($price * 100));
        } catch (Exception $exception) {
            throw new PaymentException();
        }
    }

    public function support(PaymentProcessors $paymentProcessor): bool
    {
        return $paymentProcessor === PaymentProcessors::PAYPAL;
    }
}
