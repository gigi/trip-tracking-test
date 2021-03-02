<?php

namespace App\Command;

use App\Entity\Country;
use App\Service\RestCountries\RestCountriesService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CountriesSyncCommand extends Command
{
    private const BATCH_SIZE = 20;

    protected static $defaultName = 'countries:sync';

    protected static string $defaultDescription = 'Queries countries from restcountries.eu and cashes them to database';

    private RestCountriesService $service;

    private EntityManagerInterface $entityManager;

    public function __construct(
        RestCountriesService $service,
        EntityManagerInterface $entityManager,
        string $name = null
    ) {
        parent::__construct($name);
        $this->service = $service;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument(
                'regions',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'Pass region (e.g. asia, europe)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $regions = $input->getArgument('regions');
        if (!is_array($regions)) {
            $io->error("Regions must be array");
            return Command::FAILURE;
        }

        foreach ($regions as $region) {
            $this->processRegion($region, $io);
        }

        return Command::SUCCESS;
    }

    private function processRegion(string $region, SymfonyStyle $io): void
    {
        $fetchedRegions = $this->service->getByRegion($region);

        $count = count($fetchedRegions);
        $io->info(sprintf('Fetched %d countries.', $count));

        // TODO implement raw query ON CONFLICT or ON DUPLICATE KEY UPDATE if performance issues
        foreach ($fetchedRegions as $i => $country) {
            $persistCountry = $this->entityManager->find(Country::class, $country->getCode());
            if ($persistCountry === null) {
                $io->writeln(sprintf('Adding new country %s', $country->getName()));
                $this->entityManager->persist($country);
                if (0 === ($i % self::BATCH_SIZE)) {
                    $io->info(sprintf('Saving batch %d', $i));
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }
            }
        }
        $this->entityManager->flush();
        $this->entityManager->clear();

        $io->success(sprintf('Success for %s', $region));
    }
}
