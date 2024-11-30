<?php

namespace Raid\Guardian\Drivers;

use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;
use Raid\Guardian\Drivers\Contracts\DriverInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;

class PassportDriver implements DriverInterface
{
    public function generateToken(AuthenticatableInterface $authenticatable, ?TokenInterface $token = null): string
    {
        $arguments = $token
            ? $token->toArray()
            : ['name' => (string) $authenticatable->getAuthIdentifier()];

        $generatedToken = $authenticatable->createToken(...$arguments)->accessToken;

        auth()->setUser($authenticatable);

        return $generatedToken;
    }
}
