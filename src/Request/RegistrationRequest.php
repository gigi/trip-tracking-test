<?php

declare(strict_types=1);

namespace App\Request;

use App\Request\Converter\JsonBodySerializableInterface;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationRequest implements JsonBodySerializableInterface
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    private string $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    private string $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private string $email;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }
}
