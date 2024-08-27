<?php

declare(strict_types=1);

namespace Raid\Guardian\Matchers;

use Raid\Guardian\Authenticates\Contracts\Authenticatable;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Matchers\Contracts\MatcherInterface;

abstract class Matcher implements MatcherInterface
{
    public const ATTRIBUTE = '';

    public const QUERY_ATTRIBUTE = null;

    public static function getAttribute(): string
    {
        return static::ATTRIBUTE;
    }

    protected static function getQueryAttribute(): ?string
    {
        return static::QUERY_ATTRIBUTE;
    }

    protected function getMatcherAttribute(): string
    {
        return static::getQueryAttribute() ?? static::getAttribute();
    }

    protected function getMatcherValue(array $credentials): mixed
    {
        return $credentials[static::getAttribute()];
    }

    public function find(Authenticatable $authenticatable, array $credentials): ?Authenticatable
    {
        return $authenticatable->findForAuthentication(
            $this->getMatcherAttribute(),
            $this->getMatcherValue($credentials),
        );
    }

    public function fail(AuthenticatorInterface $authenticator): void
    {
        $authenticator->fail(message: __('auth.failed'));
    }
}
