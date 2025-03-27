<?php

namespace App\Service\Payment;

use App\Enum\PaymentProcessors;
use App\Exception\PaymentException;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(name: "app.payment_processor")]
interface PaymentInterface
{
    /**
     * @throws PaymentException
     */
    public function processPayment(float $price): void;

    public function support(PaymentProcessors $paymentProcessor): bool;
}
