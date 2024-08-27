<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Authenticators;

use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;

trait HasAuthenticatable
{
    protected ?AuthenticatableInterface $authenticatable = null;

    protected bool $isAuthenticated = false;

    protected function setAuthenticatable(AuthenticatableInterface $authenticatable): void
    {
        $this->authenticatable = $authenticatable;
    }

    public function getAuthenticatable(?string $key = null, mixed $default = null): mixed
    {
        return $key
            ? $this->authenticatable->{$key}
            ?? $default
            : $this->authenticatable;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    protected function authenticate(AuthenticatableInterface $authenticatable, ?TokenInterface $token = null): void
    {
        $arguments = $token
            ? $token->toArray()
            : ['name' => $authenticatable->getAuthIdentifier()];

        $this->setToken($authenticatable->createToken(...$arguments));

        auth()->setUser($authenticatable);

        $this->authenticated = true;
    }
}
