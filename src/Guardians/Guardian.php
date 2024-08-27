<?php

declare(strict_types=1);

namespace Raid\Guardian\Guardians;

use Exception;
use Raid\Guardian\Authenticates\Contracts\Authenticatable;
use Raid\Guardian\Guardians\Contracts\GuardianInterface;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;
use Raid\Guardian\Traits\Guardians\HasAuthenticatable;
use Raid\Guardian\Traits\Guardians\HasAuthenticators;

class Guardian implements GuardianInterface
{
    use HasAuthenticatable;
    use HasAuthenticators;

    protected const NAME = '';

    public static function new(): static
    {
        return new static();
    }

    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @throws Exception
     */
    public function attempt(array $credentials, ?string $authenticator = null, ?TokenInterface $token = null): AuthenticatorInterface
    {
        return $this->getAuthenticator($authenticator)::new()
            ->attempt(
                app($this->getAuthenticatable()),
                $credentials,
                $token,
            );
    }

    /**
     * @throws Exception
     */
    public function login(Authenticatable $authenticatable, ?string $authenticator = null, ?TokenInterface $token = null): AuthenticatorInterface
    {
        return $this->getAuthenticator($authenticator)::new()
            ->login(
                $authenticatable,
                $token,
            );
    }
}
