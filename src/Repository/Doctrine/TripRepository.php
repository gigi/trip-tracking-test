<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use App\Entity\Trip;
use App\Repository\TripRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TripRepository extends ServiceEntityRepository implements TripRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }
}
