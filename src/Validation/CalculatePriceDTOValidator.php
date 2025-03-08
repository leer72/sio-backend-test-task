<?php

namespace App\Validation;

class CalculatePriceDTOValidator extends AbstractValidator
{
    protected function getConstraints(): array
    {
        return [
            'productId' => $this->getIdRules(),
            'taxNumber' => $this->getTaxNumberRules(),
            'couponCode' => $this->getCouponRules(),
        ];
    }
}
