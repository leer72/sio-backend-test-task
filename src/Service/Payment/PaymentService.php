<?php

namespace App\Service\Payment;

use App\Exception\PaymentException;

class PaymentService
{
    public function __construct(
        private readonly PaymentFactory $paymentFactory,
    ) {
    }

    /**
     * @throws PaymentException
     */
    public function process(string $paymentType, float $price): void
    {
        $paymentProcessor = $this->paymentFactory->getPaymentProcessor($paymentType);

        $paymentProcessor->processPayment($price);
    }
}
