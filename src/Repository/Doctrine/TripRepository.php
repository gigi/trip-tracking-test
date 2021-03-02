<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use App\Entity\Trip;
use App\Entity\User;
use App\Repository\TripRepositoryInterface;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trip|null findOneBy(array $criteria, array $orderBy = null)
 */
class TripRepository extends ServiceEntityRepository implements TripRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    public function add(Trip $trip): void
    {
        $this->getEntityManager()->persist($trip);
    }

    public function findByRange(User $user, DateTimeInterface $startDate, DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.startDate < :endDate')
            ->andWhere('u.endDate > :startDate')
            ->andWhere('u.user = :user')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('user', $user)
            ->orderBy('u.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function filter(
        User $user,
        ?string $countryCode,
        ?DateTimeInterface $startDate,
        ?DateTimeInterface $endDate
    ): array {
        $queryBuilder = $this->createQueryBuilder('u');
        if (!empty($countryCode)) {
            $queryBuilder
                ->andWhere('u.countryCode = :code')
                ->setParameter('code', $countryCode);
        }
        if (null !== $endDate) {
            $queryBuilder
                ->andWhere('u.startDate < :endDate')
                ->setParameter('endDate', $endDate);
        }
        if (null !== $startDate) {
            $queryBuilder
                ->andWhere('u.endDate > :startDate')
                ->setParameter('startDate', $startDate);
        }

        return $queryBuilder->andWhere('u.user = :user')
            ->setParameter('user', $user)
            ->orderBy('u.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @param int $id
     * @return Trip|null
     */
    public function findById(User $user, int $id): ?Trip
    {
        return $this->findOneBy(['user' => $user, 'id' => $id]);
    }
}
