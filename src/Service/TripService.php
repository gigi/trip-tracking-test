<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Trip;
use App\Entity\User;
use App\Exception\TripException;
use App\Repository\CountryRepositoryInterface;
use App\Repository\TripRepositoryInterface;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

class TripService
{
    private TripRepositoryInterface $tripRepository;
    private CountryRepositoryInterface $countryRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        TripRepositoryInterface $tripRepository,
        EntityManagerInterface $entityManager,
        CountryRepositoryInterface $countryRepository
    ) {
        $this->tripRepository = $tripRepository;
        $this->entityManager = $entityManager;
        $this->countryRepository = $countryRepository;
    }

    public function addTrip(
        User $user,
        string $countryCode,
        DateTimeInterface $startDate,
        DateTimeInterface $endDate,
        string $notes = null
    ): Trip {
        $trips = $this->tripRepository->findByRange($user, $startDate, $endDate);
        if (count($trips) > 0) {
            throw new TripException("Selected dates already reserved for trips");
        }
        $country = $this->countryRepository->findOneByCode($countryCode);
        if (null === $country) {
            throw new TripException(sprintf("Country %s not found", $countryCode));
        }
        $trip = new Trip($user, $countryCode, $startDate, $endDate, $notes);
        $this->tripRepository->add($trip);
        $this->entityManager->flush();

        return $trip;
    }

    /**
     * @param User $user
     * @param string|null $code
     * @param DateTimeInterface|null $startDate
     * @param DateTimeInterface|null $endDate
     * @return Trip[]
     */
    public function search(User $user, ?string $code, ?DateTimeInterface $startDate, ?DateTimeInterface $endDate): array
    {
        $code = null !== $code ? strtoupper($code) : null;
        return $this->tripRepository->filter($user, $code, $startDate, $endDate);
    }

    /**
     * @param User $user
     * @param int $id
     * @return Trip
     * @throws TripException
     */
    public function getOne(User $user, int $id): Trip
    {
        $trip = $this->tripRepository->findById($user, $id);
        if (null !== $trip) {
            return $trip;
        }

        throw new TripException('Trip not found', 404);
    }

    /**
     * @param User $user
     * @param int $id
     * @throws TripException
     */
    public function delete(User $user, int $id): void
    {
        $trip = $this->getOne($user, $id);

        $this->entityManager->remove($trip);
        $this->entityManager->flush();
    }
}
