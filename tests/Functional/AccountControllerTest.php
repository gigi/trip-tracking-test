<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\User;
use App\Repository\Doctrine\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccountControllerTest extends WebTestCase
{
    public function testRegister(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/v1/register',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            '{"username":"user1","password":"password1","email":"email1@example.com"}'
        );

        self::assertEquals(200, $client->getResponse()->getStatusCode());

        $entityManager = $this->getEntityManager();

        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);
        /** @var User $user */
        $user = $userRepository->findByUsername('user1');
        self::assertNotNull($user);
        self::assertEquals('email1@example.com', $user->getEmail());
        self::assertNotEmpty($user->getPassword());
        self::assertNotEquals('password1', $user->getPassword());
    }

    private function getEntityManager(): EntityManagerInterface
    {
        /** @var EntityManagerInterface $manager */
        $manager = self::$container->get(EntityManagerInterface::class);

        return $manager;
    }
}
