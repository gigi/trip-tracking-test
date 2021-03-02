<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function findByUsername(string $username): ?User;

    public function add(User $user): void;
}
