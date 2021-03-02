<?php

declare(strict_types=1);

namespace App\Service\RestCountries;

use Symfony\Component\Serializer\SerializerInterface;

class RestCountriesConverter implements RestCountriesConverterInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function convert(string $json): array
    {
        return $this->serializer->deserialize(
            $json,
            'App\Entity\Country[]',
            'json'
        );
    }
}
