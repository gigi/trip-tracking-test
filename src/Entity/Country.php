<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Country
{
    private ?int $id;

    #[SerializedName('alpha3Code')]
    private string $code;

    private string $name;

    private string $region;

    public function __construct(string $code, string $region, string $name)
    {
        $this->code = $code;
        $this->region = $region;
        $this->name = $name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRegion(): string
    {
        return $this->region;
    }
}
