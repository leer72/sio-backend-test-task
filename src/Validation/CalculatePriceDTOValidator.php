<?php

namespace App\Validation;

class CalculatePriceDTOValidator extends AbstractValidator
{
    protected function getConstraints(): array
    {
        return [
            'product' => $this->getIdRules(),
            'taxNumber' => $this->getTaxNumberRules(),
            'couponCode' => $this->getCouponRules(),
        ];
    }
}
