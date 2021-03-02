<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;

class Trip
{
    private ?int $id;

    private User $user;

    private string $countryCode;

    private DateTimeInterface $startDate;

    private DateTimeInterface $endDate;

    private ?string $notes;

    public function __construct(
        User $user,
        string $countryCode,
        DateTimeInterface $startDate,
        DateTimeInterface $endDate,
        ?string $notes
    ) {
        $this->user = $user;
        $this->countryCode = $countryCode;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->notes = $notes;
    }
}
