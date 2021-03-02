<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use JsonSerializable;
use Symfony\Component\Serializer\Annotation\Ignore;

class Trip implements JsonSerializable
{
    private ?int $id;

    /**
     * @Ignore()
     */
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
        string $notes = null
    ) {
        $this->user = $user;
        $this->countryCode = $countryCode;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->notes = $notes;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'countryCode' => $this->countryCode
        ];
    }
}
