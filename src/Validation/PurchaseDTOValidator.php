<?php

namespace App\Validation;

class PurchaseDTOValidator extends CalculatePriceDTOValidator
{
    protected function getConstraints(): array
    {
        return array_merge(
            parent::getConstraints(),
            [
                'paymentProcessor' => $this->getStringRules(),
            ]
        );
    }
}
