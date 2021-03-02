<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    /** @var array<array<string>>  */
    private array $users = [
        ['user1', 'password1', 'email1@example.com'],
        ['user2', 'password2', 'email2@example.com'],
    ];

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->users as $userData) {
            $user = new User($userData[0], $userData[2]);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userData[1]));
            $manager->persist($user);
            $manager->flush();
        }
    }
}
