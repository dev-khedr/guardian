<?php

declare(strict_types=1);

namespace Raid\Core\Authentication\Traits\Channels;

use Illuminate\Contracts\Auth\Authenticatable;
use Raid\Core\Authentication\Tokens\Contracts\TokenInterface;

trait HasAuthenticatable
{
    protected ?Authenticatable $authenticatable = null;

    protected bool $isAuthenticated = false;

    protected function setAuthenticatable(Authenticatable $authenticatable): void
    {
        $this->authenticatable = $authenticatable;
    }

    public function getAuthenticatable(?string $key = null, mixed $default = null): mixed
    {
        return $key ?
            $this->authenticatable->{$key} ?? $default :
            $this->authenticatable;
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    protected function authenticate(Authenticatable $authenticatable, ?TokenInterface $token = null): void
    {
        $arguments = $token ?
            $token->toArray() :
            ['name' => $authenticatable->getAuthIdentifier()];

        $this->setToken($authenticatable->createToken(...$arguments));

        auth()->setUser($authenticatable);

        $this->authenticated = true;
    }
}
