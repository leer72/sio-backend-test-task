<?php

namespace App\Service;

use App\Enum\Countries;

class TaxNumberService
{
    private const array TAX_MAP = [
        Countries::GERMANY->value => 19,
        Countries::FRANCE->value => 20,
        Countries::ITALY->value => 22,
        Countries::GREECE->value => 24,
    ];

    private function resolveCountryByCode(string $countryCode): Countries
    {
        return match (true) {
            str_starts_with($countryCode, Countries::GERMANY->value) => Countries::GERMANY,
            str_starts_with($countryCode, Countries::FRANCE->value) => Countries::FRANCE,
            str_starts_with($countryCode, Countries::ITALY->value) => Countries::ITALY,
            str_starts_with($countryCode, Countries::GREECE->value) => Countries::GREECE,
        };
    }

    public function getPriceWithTax(string $taxNumber, float $price): float
    {
        return round(
            num: $price
                + $price
                * TaxNumberService::TAX_MAP[$this->resolveCountryByCode($taxNumber)->value]
                / 100,
            precision: 2
        );
    }
}
