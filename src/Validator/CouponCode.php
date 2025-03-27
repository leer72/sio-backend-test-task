<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CouponCode extends Constraint
{
    public string $message = 'dto.coupon_code.coupon_code';
    public string $mode = 'strict';

    public function __construct(?string $mode = null, ?string $message = null, ?array $groups = null, $payload = null)
    {
      parent::__construct([], $groups, $payload);

      $this->mode = $mode ?? $this->mode;
      $this->message = $message ?? $this->message;
    }
}
