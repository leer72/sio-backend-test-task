<?php

namespace App\Service\Payment;

use App\Enum\PaymentProcessors;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class PaymentProcessorProvider
{
    private iterable $paymentProcessors = [];

    public function __construct(
        #[TaggedIterator('app.payment_processor')] iterable $paymentProcessors
    ) {
        $this->paymentProcessors = $paymentProcessors;
    }

    /**
     * @throws Exception
     */
    public function handlePaymentProcessors(PaymentProcessors $needle): PaymentInterface
    {
        /** @var PaymentInterface $paymentProcessor */
        foreach ($this->paymentProcessors as $paymentProcessor) {
            if ($paymentProcessor->support($needle)) {
                return $paymentProcessor;
            }
        }

        throw new Exception();
    }
}
