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

    protected function getWorkerAttribute(): string
    {
        return static::getQueryAttribute() ?? static::getAttribute();
    }

    protected function getWorkerValue(array $credentials): mixed
    {
        return $credentials[static::getAttribute()];
    }

    public function find(Authenticatable $authenticatable, array $credentials): ?Authenticatable
    {
        return $authenticatable->findForAuthentication(
            $this->getWorkerAttribute(),
            $this->getWorkerValue($credentials),
        );
    }

    public function fail(AuthenticatorInterface $channel): void
    {
        $channel->fail(message: __('auth.failed'));
    }
}
