<?php

namespace App\Tests\Integration\Service\RestCountries;

use App\Entity\Country;
use App\Service\RestCountries\RestCountriesConverterInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RestCountriesConverterTest extends WebTestCase
{
    public function testConvert(): void
    {
        $client = self::createClient();
        $container = $client->getContainer();
        /** @var RestCountriesConverterInterface $converter */
        $converter = $container->get('test.' . RestCountriesConverterInterface::class);
        $result = $converter->convert(
            '[{"alpha3Code":"code1","name":"name1","region":"region1"},{"alpha3Code":"code1","name":"name1","region":"region1"}]',
        );
        self::assertEquals([
            new Country('code1', 'region1', 'name1'),
            new Country('code1', 'region1', 'name1'),
        ], $result);
    }
}
