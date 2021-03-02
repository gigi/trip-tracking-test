<?php

declare(strict_types=1);

namespace App\Request;

use App\Request\Converter\JsonBodySerializableInterface;
use DateTimeInterface;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTripRequest implements JsonBodySerializableInterface
{
    /**
     * @Assert\NotBlank
     */
    private string $countryCode;
    /**
     * @Assert\NotBlank
     */
    private DateTimeInterface $startDate;
    /**
     * @Assert\NotBlank
     */
    private DateTimeInterface $endDate;
    private ?string $notes;

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): CreateTripRequest
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    public function getStartDate(): DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(DateTimeInterface $startDate): CreateTripRequest
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(DateTimeInterface $endDate): CreateTripRequest
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): CreateTripRequest
    {
        $this->notes = $notes;
        return $this;
    }
}
