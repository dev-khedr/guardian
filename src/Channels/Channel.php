<?php

declare(strict_types=1);

namespace Raid\Core\Authentication\Channels;

use Illuminate\Contracts\Auth\Authenticatable;
use Raid\Core\Authentication\Authenticates\Contracts\Authenticates;
use Raid\Core\Authentication\Channels\Contracts\ChannelInterface;
use Raid\Core\Authentication\Channels\Contracts\ShouldRunRules;
use Raid\Core\Authentication\Channels\Contracts\ShouldRunSteps;
use Raid\Core\Authentication\Tokens\Contracts\TokenInterface;
use Raid\Core\Authentication\Traits\Channels\HasAuthenticatable;
use Raid\Core\Authentication\Traits\Channels\HasCredentials;
use Raid\Core\Authentication\Traits\Channels\HasErrors;
use Raid\Core\Authentication\Traits\Channels\HasRules;
use Raid\Core\Authentication\Traits\Channels\HasSteps;
use Raid\Core\Authentication\Traits\Channels\HasToken;
use Raid\Core\Authentication\Traits\Channels\HasWorkers;

abstract class Channel implements ChannelInterface
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

    public function attempt(Authenticates $authenticates, array $credentials, ?TokenInterface $token = null): static
    {
        $this->setCredentials($credentials);

        $authenticatable = $this->findAuthenticatable($authenticates, $credentials);

        if ($this->failed()) {
            return $this;
        }

        if ($this instanceof ShouldRunRules && ! $this->runRules()) {

            return $this;
        }

        if ($this instanceof ShouldRunSteps) {
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
