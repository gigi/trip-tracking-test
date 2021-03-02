<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Trip;
use App\Entity\User;
use App\Repository\CountryRepositoryInterface;
use App\Repository\TripRepositoryInterface;
use App\Service\TripService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class TripServiceTest extends TestCase
{
    public function testDelete(): void
    {
        $userMock = $this->createMock(User::class);
        $tripMock = $this->createMock(Trip::class);
        $tripRepoMock = $this->createMock(TripRepositoryInterface::class);
        $tripRepoMock
            ->expects(self::once())
            ->method('findById')
            ->with($userMock, 1)
            ->willReturn($tripMock);

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $entityManagerMock
            ->expects(self::once())
            ->method('remove')
            ->with($tripMock);

        $entityManagerMock
            ->expects(self::once())
            ->method('flush');

        $service = new TripService(
            $tripRepoMock,
            $entityManagerMock,
            $this->createMock(CountryRepositoryInterface::class)
        );
        $service->delete($userMock, 1);
    }
}
