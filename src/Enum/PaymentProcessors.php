<?php

namespace App\Enum;

enum PaymentProcessors: string
{
    case PAYPAL = 'paypal';

    case STRIPE = 'stripe';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
