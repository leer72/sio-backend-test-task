<?php

namespace App\Validation;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractValidator
{
    private const string MISSING_FIELDS_MESSAGE = 'Значение обязательно к заполнению';

    public function __construct(
        private readonly ValidatorInterface $validator,
    ) {
    }

    abstract protected function getConstraints(): array;

    public function validate(array $requestFields): array
    {
        $errors = [];

        $violations = $this->validator->validate(
            $requestFields,
            new Collection(
                fields: $this->getConstraints(),
                missingFieldsMessage: self::MISSING_FIELDS_MESSAGE,
            ),
        );

        /**
         * @var ConstraintViolation $violation
         */
        foreach ($violations as $violation) {
            $field = preg_replace(['/\]\[/', '/\[|\]/'], ['.', ''], $violation->getPropertyPath());
            $errors[$field] = $violation->getMessage();
        }

        return $errors;
    }

    protected function getStringRules(): array
    {
        return [
            new Assert\Type([
                'type' => 'string',
                'message' => 'Значение должно быть строкой',
            ]),
        ];
    }

    protected function getNotBlank(): Assert\NotBlank
    {
        return new Assert\NotBlank(['message' => 'Поле обязательно к заполнению']);
    }

    protected function getIdRules(): array
    {
        return [
            $this->getNotBlank(),
            new Assert\Range([
                'min' => 1,
                'minMessage' => 'ID не может быть меньше 1',
            ]),
            new Assert\Regex([
                'pattern' => "/^[0-9]+$/",
                'message' => 'ID должен быть целым числом',
            ]),
        ];
    }

    protected function getTaxNumberRules(): array
    {
        return [
            new Assert\Regex([
                'pattern' => '/^(DE\d{9}|IT\d{11}|GR\d{9}|FR[A-Z]{2}\d{9})$/',
                'message' => 'Неверный налоговый номер',
            ]),
        ];
    }

    protected function getCouponRules(): array
    {
        return [
            new Assert\Regex([
                'pattern' => '/^(P|D)\d{1,9}$/',
                'message' => 'Неверный код купона',
            ]),
        ];
    }
}
