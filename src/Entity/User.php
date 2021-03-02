<?php

declare(strict_types=1);

namespace App\Entity;

use App\Request\RegistrationRequest;
use JsonSerializable;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, JsonSerializable
{
    private ?int $id;

    private string $username;

    private string $email;

    private ?string $password;

    public function __construct(string $username, string $email)
    {
        $this->username = $username;
        $this->email = $email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $passwordHash): void
    {
        $this->password = $passwordHash;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return [];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function updateFromRegistrationRequest(RegistrationRequest $registration): self
    {
        if (!empty($registration->getUsername())) {
            $this->username = $registration->getUsername();
        }
        if (!empty($registration->getEmail())) {
            $this->email = $registration->getEmail();
        }

        return $this;
    }

    public function jsonSerialize(): array
    {
        return ['id' => $this->id];
    }
}
