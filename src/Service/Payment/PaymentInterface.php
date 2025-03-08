<?php

namespace App\Service\Payment;

use App\Exception\PaymentException;

interface PaymentInterface
{
    /**
     * @throws PaymentException
     */
    public function processPayment(float $price): void;
}
