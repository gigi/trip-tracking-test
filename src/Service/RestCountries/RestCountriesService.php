<?php

declare(strict_types=1);

namespace App\Service\RestCountries;

use App\Entity\Country;
use App\Exception\RestCountriesException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Symfony\Component\HttpClient\Psr18Client;

class RestCountriesService
{
    private const BASE_URI = 'https://restcountries.eu/rest/v2/';

    /** @var array|string[] */
    private array $filterFields = [
        'name',
        'alpha3Code',
        'region',
    ];

    private ClientInterface $httpClient;

    private RestCountriesConverter $converter;

    public function __construct(ClientInterface $httpClient, RestCountriesConverter $converter)
    {
        $this->httpClient = $httpClient;
        $this->converter = $converter;
    }

    /**
     * @param string $region
     * @return Country[]
     * @throws RestCountriesException
     */
    public function getByRegion(string $region): array
    {
        $url = sprintf('region/%s', strtolower($region));
        return $this->executeRequest($url);
    }

    /**
     * @param string $method
     * @return Country[]
     * @throws RestCountriesException
     */
    private function executeRequest(string $method): array
    {
        $baseUri = sprintf(
            '%s%s',
            self::BASE_URI,
            $method
        );
        /** @var Psr18Client $client */
        $client = $this->httpClient;
        $request = $client->createRequest(
            'GET',
            sprintf('%s?fields=%s', $baseUri, implode(';', $this->filterFields))
        );
        try {
            $json = $this->httpClient
                ->sendRequest($request)
                ->getBody()
                ->getContents();
            return $this->converter->convert($json);
        } catch (ClientExceptionInterface $e) {
            throw new RestCountriesException('Error on countries sync', 0, $e);
        }
    }
}
