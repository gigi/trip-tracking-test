<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Trip;
use App\Entity\User;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TripFixture extends Fixture implements DependentFixtureInterface
{
    /** @var array<int, array<string>> */
    private array $trips = [
        ['username1', 'ESP', '2021-01-01', '2021-01-31', 'note 1'],
        ['username1', 'UKR', '2021-02-01', '2021-02-28', ''],
        ['username2', 'FRA', '2021-02-01', '2021-02-28', ''],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->trips as $data) {
            /** @var User $user */
            $user = $this->getReference($data[0]);
            /** @var DateTimeInterface $start */
            $start = DateTime::createFromFormat('Y-m-d', $data[2]);
            /** @var DateTimeInterface $end */
            $end = DateTime::createFromFormat('Y-m-d', $data[3]);
            $trip = new Trip(
                $user,
                $data[1],
                $start,
                $end,
                $data[4]
            );
            $manager->persist($trip);
            $manager->flush();
        }
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [UserFixture::class];
    }
}
