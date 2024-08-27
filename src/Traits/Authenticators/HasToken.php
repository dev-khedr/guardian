<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Authenticators;

use Laravel\Sanctum\NewAccessToken;

trait HasToken
{
    protected NewAccessToken $token;

    protected function setToken(NewAccessToken $token): void
    {
        $this->token = $token;
    }

    public function getToken(?string $key = null, mixed $default = null): mixed
    {
        return $key ?
            $this->token->{$key} ?? $default :
            $this->token;
    }

    public function getStringToken(): string
    {
        return (string) $this->getToken('plainTextToken');
    }
}
