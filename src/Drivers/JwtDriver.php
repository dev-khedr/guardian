<?php

namespace Raid\Guardian\Drivers;

use Illuminate\Support\Arr;
use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;
use Raid\Guardian\Drivers\Contracts\DriverInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;
use Exception;

class JwtDriver implements DriverInterface
{
    /**
     * @throws Exception
     */
    public function generateToken(AuthenticatableInterface $authenticatable, ?TokenInterface $token = null): string
    {
        return auth($this->getGuard($authenticatable))
            ->setTTL($this->getTimeToLive($token))
            ->login($authenticatable);
    }

    /**
     * @throws Exception
     */
    private function getGuard(AuthenticatableInterface $authenticatable): string
    {
        $guards = config('auth.guards', []);
        $providers = config('auth.providers', []);

        foreach ($guards as $guardName => $guardConfig) {
            if (
                $guardConfig['driver'] !== 'jwt' ||
                !isset($guardConfig['provider'])
            ) {
                continue;
            }

            $providerName = $guardConfig['provider'];

            if (
                !isset($providers[$providerName]) ||
                !isset($providers[$providerName]['model']) ||
                $providers[$providerName]['model'] !== get_class($authenticatable)
            ) {
                continue;
            }

            return $guardName;
        }

        throw new Exception("No guard found for model: " . get_class($authenticatable));
    }

    private function getTimeToLive(?TokenInterface $token): int
    {
        $expiresAt = $token?->getExpiresAt();

        return $expiresAt
            ? now()->diffInMinutes($expiresAt)
            : config('jwt.ttl');
    }
}
