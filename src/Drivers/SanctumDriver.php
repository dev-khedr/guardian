<?php

namespace Raid\Guardian\Drivers;

use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;
use Raid\Guardian\Drivers\Contracts\DriverInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;

class SanctumDriver implements DriverInterface
{
    public function generateToken(AuthenticatableInterface $authenticatable, ?TokenInterface $token = null): string
    {
        $arguments = $token
            ? $token->toArray()
            : ['name' => (string) $authenticatable->getAuthIdentifier()];

        $generatedToken = $authenticatable->createToken(...$arguments)->plainTextToken;

        auth()->setUser($authenticatable);

        return $generatedToken;
    }
}
