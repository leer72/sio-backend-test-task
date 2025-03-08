<?php

namespace App\ArgumentResolver;

use App\DTO\CalculatePriceDTO;
use Exception;
use Generator;

class CalculatePriceDTOResolver extends ArgumentResolver
{
    public function __construct(CalculatePriceDTOValidator $validator)
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

        yield new CalculatePriceDTO(
            productId: $params['productId'],
            taxNumber: $params['taxNumber'],
            couponCode: $params['couponCode'],
        );
    }

    public function getSupportsClass(): string
    {
        return CalculatePriceDTO::class;
    }
}
