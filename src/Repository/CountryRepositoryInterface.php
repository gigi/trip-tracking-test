<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Country;

interface CountryRepositoryInterface
{
    public function findOneByCode(string $countryCode): ?Country;
}
