<?php

declare(strict_types=1);

namespace App\Entity;

use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

class Login implements JsonSerializable
{
    private string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
