<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Exception\RegistrationException;
use App\Repository\UserRepositoryInterface;
use App\Request\RegistrationRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountService
{
    private UserRepositoryInterface $userRepository;

    private UserPasswordEncoderInterface $passwordEncoder;

    private EntityManagerInterface $entityManager;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepositoryInterface $userRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @return User
     * @throws RegistrationException
     */
    public function create(string $username, string $email, string $password): User
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user !== null) {
            throw new RegistrationException('This email is already registered');
        }
        $user = $this->userRepository->findByUsername($username);
        if ($user !== null) {
            throw new RegistrationException('This username is already registered');
        }

        $user = new User($username, $email);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

        $this->userRepository->add($user);
        $this->entityManager->flush();

        return $user;
    }

    public function deleteAccount(UserInterface $user): void
    {
        $user = $this->getUserByUsername($user->getUsername());
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function updateAccount(UserInterface $user, RegistrationRequestType $registration): void
    {
        // TODO implement unique validation for username and email
        $user = $this->getUserByUsername($user->getUsername());
        $user->updateFromRegistrationType($registration);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $registration->getPassword()));
        $this->entityManager->flush();
    }

    private function getUserByUsername(string $username): User
    {
        $user = $this->userRepository->findByUsername($username);
        if ($user !== null) {
            return $user;
        }

        throw new UsernameNotFoundException('Username not found');
    }
}
