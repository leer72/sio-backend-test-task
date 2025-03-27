<?php

namespace App\Validator;

use App\Enum\CouponType;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class CouponCodeValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CouponCode) {
            throw new UnexpectedTypeException($constraint, CouponCode::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
          throw new UnexpectedValueException($value, 'string');
        }

        if (
            preg_match('/^('. implode('|', CouponType::getValues()) .')\d{1,9}$/', $value, $matches)
        ) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ string }}', $value)
            ->addViolation();
    }
}
