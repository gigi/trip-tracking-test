<?php

declare(strict_types=1);

namespace App\Service\RestCountries;

use App\Entity\Country;

interface RestCountriesConverterInterface
{
    /**
     * @return Country[]
     */
    public function convert(string $json): array;
}
