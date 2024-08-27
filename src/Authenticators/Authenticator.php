<?php

declare(strict_types=1);

namespace Raid\Guardian\Authenticators;

use Raid\Guardian\Authenticates\Contracts\Authenticatable;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Authenticators\Contracts\ShouldRunNorms;
use Raid\Guardian\Authenticators\Contracts\ShouldRunSequences;
use Raid\Guardian\Tokens\Contracts\TokenInterface;
use Raid\Guardian\Traits\Authenticators\HasAuthenticatable;
use Raid\Guardian\Traits\Authenticators\HasCredentials;
use Raid\Guardian\Traits\Authenticators\HasErrors;
use Raid\Guardian\Traits\Authenticators\HasNorms;
use Raid\Guardian\Traits\Authenticators\HasSequences;
use Raid\Guardian\Traits\Authenticators\HasToken;
use Raid\Guardian\Traits\Authenticators\HasMatchers;

abstract class Authenticator implements AuthenticatorInterface
{
    use HasAuthenticatable;
    use HasCredentials;
    use HasErrors;
    use HasNorms;
    use HasSequences;
    use HasToken;
    use HasMatchers;

    public const NAME = '';

    public static function new(): static
    {
        return new static();
    }

    public static function getName(): string
    {
        return static::NAME;
    }

    public function attempt(Authenticatable $authenticatable, array $credentials, ?TokenInterface $token = null): static
    {
        $this->setCredentials($credentials);

        $foundAuthenticatable = $this->findAuthenticatable($authenticatable, $credentials);

        if ($this->failed()) {
            return $this;
        }

        if ($this instanceof ShouldRunNorms && ! $this->runNorms()) {

            return $this;
        }

        if ($this instanceof ShouldRunSequences) {
            $this->runSequences();

            return $this;
        }

        $this->authenticate($foundAuthenticatable, $token);

        return $this;
    }

    public function login(Authenticatable $authenticatable, ?TokenInterface $token = null): static
    {
        $this->setAuthenticatable($authenticatable);

        $this->authenticate($authenticatable, $token);

        return $this;
    }
}
