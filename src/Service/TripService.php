<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Trip;
use App\Entity\User;
use App\Repository\TripRepositoryInterface;
use DateTimeInterface;

class TripService
{
    public function __construct(TripRepositoryInterface $tripRepository)
    {
    }

    public function addTrip(User $user, string $countyCode, DateTimeInterface $startDate, DateTimeInterface $endDate): Trip
    {
    }
}
