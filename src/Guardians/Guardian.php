<?php

declare(strict_types=1);

namespace Raid\Guardian\Guardians;

use Exception;
use Raid\Guardian\Authenticates\Contracts\Authenticatable;
use Raid\Guardian\Guardians\Contracts\GuardianInterface;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;
use Raid\Guardian\Traits\Authenticators\HasAuthenticates;
use Raid\Guardian\Traits\Authenticators\HasChannels;

class Guardian implements GuardianInterface
{
    use HasAuthenticates;
    use HasChannels;

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
    public function attempt(array $credentials, ?string $channel = null, ?TokenInterface $token = null): AuthenticatorInterface
    {
        return $this->getChannel($channel)::new()->attempt(
            app($this->getAuthenticates()),
            $credentials,
            $token,
        );
    }

    /**
     * @throws Exception
     */
    public function login(Authenticatable $authenticatable, ?string $channel = null, ?TokenInterface $token = null): AuthenticatorInterface
    {
        return $this->getChannel($channel)::new()->login(
            $authenticatable,
            $token,
        );
    }
}
