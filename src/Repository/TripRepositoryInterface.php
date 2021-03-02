<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Trip;
use App\Entity\User;
use DateTimeInterface;

interface TripRepositoryInterface
{
    public function add(Trip $trip): void;

    /**
     * Finds trips that exist in given date range
     *
     * @param User $user
     * @param DateTimeInterface $startDate
     * @param DateTimeInterface $endDate
     * @return Trip[]
     */
    public function findByRange(User $user, DateTimeInterface $startDate, DateTimeInterface $endDate): array;

    /**
     * Filters user's trips
     *
     * @param User $user
     * @param string|null $countryCode
     * @param DateTimeInterface|null $startDate
     * @param DateTimeInterface|null $endDate
     * @return Trip[]
     */
    public function filter(
        User $user,
        ?string $countryCode,
        ?DateTimeInterface $startDate,
        ?DateTimeInterface $endDate
    ): array;

    /**
     * @param User $user
     * @param int $id
     * @return Trip|null
     */
    public function findById(User $user, int $id): ?Trip;
}
