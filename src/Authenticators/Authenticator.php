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
use Raid\Guardian\Traits\Authenticators\HasRules;
use Raid\Guardian\Traits\Authenticators\HasSteps;
use Raid\Guardian\Traits\Authenticators\HasToken;
use Raid\Guardian\Traits\Authenticators\HasWorkers;

abstract class Authenticator implements AuthenticatorInterface
{
    use HasAuthenticatable;
    use HasCredentials;
    use HasErrors;
    use HasRules;
    use HasSteps;
    use HasToken;
    use HasWorkers;

    public const NAME = '';

    public static function new(): static
    {
        return new static();
    }

    public static function getName(): string
    {
        return static::NAME;
    }

    public function attempt(Authenticatable $authenticates, array $credentials, ?TokenInterface $token = null): static
    {
        $this->setCredentials($credentials);

        $authenticatable = $this->findAuthenticatable($authenticates, $credentials);

        if ($this->failed()) {
            return $this;
        }

        if ($this instanceof ShouldRunNorms && ! $this->runRules()) {

            return $this;
        }

        if ($this instanceof ShouldRunSequences) {
            $this->runSteps();

            return $this;
        }

        $this->authenticate($authenticatable, $token);

        return $this;
    }

    public function login(Authenticatable $authenticatable, ?TokenInterface $token = null): static
    {
        $this->setAuthenticatable($authenticatable);

        $this->authenticate($authenticatable, $token);

        return $this;
    }
}
