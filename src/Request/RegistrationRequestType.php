<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationRequestType implements JsonBodySerializableInterface
{
    /**
     * @Assert\NotBlank()
     */
    private string $username;

    /**
     * @Assert\NotBlank()
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
