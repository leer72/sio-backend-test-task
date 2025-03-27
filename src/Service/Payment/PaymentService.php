<?php

namespace App\Service\Payment;

use App\Enum\PaymentProcessors;
use App\Exception\PaymentException;
use Exception;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaymentService
{
    public function __construct(
        private readonly PaymentProcessorProvider $paymentProcessorProvider,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * @throws Exception
     */
    public function process(PaymentProcessors $paymentType, float $price): void
    {
        try {
            $paymentProcessor = $this->paymentProcessorProvider->handlePaymentProcessors($paymentType);

            $paymentProcessor->processPayment($price);
        } catch (PaymentException $exception) {
            throw new Exception('payment_processor.exception');
        } catch (Exception $exception) {
            throw new Exception('payment_processor.not_found');
        }
    }
}
