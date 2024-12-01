<?php

namespace Raid\Guardian\Drivers;

use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;
use Raid\Guardian\Drivers\Contracts\DriverInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;

class SanctumDriver implements DriverInterface
{
    public function generateToken(AuthenticatableInterface $authenticatable, ?TokenInterface $token = null): string
    {
        $generatedToken = $authenticatable->createToken(
            ...$this->resolveToken($authenticatable, $token),
        )->plainTextToken;

        auth()->setUser($authenticatable);

        return $generatedToken;
    }

    private function resolveToken(AuthenticatableInterface $authenticatable, ?TokenInterface $token = null): array
    {
        return $token
            ? [$token->toArray()]
            : [(string) $authenticatable->getAuthIdentifier()];
    }
}
