<?php

namespace App\ArgumentResolver;

use App\DTO\PurchaseDTO;
use App\Validation\PurchaseDTOValidator;
use Exception;
use Generator;

class PurchaseDTOResolver extends ArgumentResolver
{
    public function __construct(PurchaseDTOValidator $validator)
    {
        parent::__construct($validator);
    }

    /**
     * @throws Exception
     */
    public function handle(): Generator
    {
        $params = $this->getJson();

        $this->validate($params);

        yield new PurchaseDTO(
            productId: $params['productId'],
            taxNumber: $params['taxNumber'],
            couponCode: $params['couponCode'],
            paymentProcessor: $params['paymentProcessor'],
        );
    }

    public function getSupportsClass(): string
    {
        return PurchaseDTO::class;
    }
}
