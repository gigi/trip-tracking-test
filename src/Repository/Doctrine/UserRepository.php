<?php

declare(strict_types=1);

namespace App\Repository\Doctrine;

use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy([
            'email' => $email,
        ]);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->findOneBy([
            'username' => $username,
        ]);
    }

    /**
     * @param User $user
     * @throws ORMException
     */
    public function add(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }
}
