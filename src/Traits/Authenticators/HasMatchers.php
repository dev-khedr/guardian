<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Authenticators;

use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;
use Raid\Guardian\Matchers\Contracts\MatcherInterface;

trait HasMatchers
{
    protected array $matchers;

    protected MatcherInterface $matcher;

    public function setMatchers(array $matchers): static
    {
        $this->matchers = $matchers;

        return $this;
    }

    public function getMatchers(): array
    {
        return $this->matchers;
    }

    protected function setMatcher(MatcherInterface $matcher): void
    {
        $this->matcher = $matcher;
    }

    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    protected function getConfiguredMatchers(): array
    {
        return isset($this->matchers)
            ? $this->getMatchers()
            : config('guardian.authenticator_matchers.'.static::class, []);
    }

    protected function findAuthenticatable(AuthenticatableInterface $authenticatable, array $credentials): ?AuthenticatableInterface
    {
        $matcher = $this->getAuthenticatorMatcher($credentials);

        return $matcher
            ? $this->findMatcherAuthenticatable($matcher, $authenticatable, $credentials)
            : null;
    }

    protected function getAuthenticatorMatcher(array $credentials): ?MatcherInterface
    {
        $matcher = $this->getMatcherForCredentials($credentials);

        $matcher
            ? $this->setMatcher($matcher)
            : $this->fail(message: __('auth.matcher_not_found'));

        return $matcher;
    }

    protected function getMatcherForCredentials(array $credentials): ?MatcherInterface
    {
        foreach ($this->getConfiguredMatchers() as $matcher) {
            if (! array_key_exists($matcher::getAttribute(), $credentials)) {
                continue;
            }

            return app($matcher);
        }

        return null;
    }

    protected function findMatcherAuthenticatable(MatcherInterface $matcher, AuthenticatableInterface $authenticatable, array $credentials): ?AuthenticatableInterface
    {
        $foundAuthenticatable = $matcher->find($authenticatable, $credentials);

        $foundAuthenticatable
            ? $this->setAuthenticatable($foundAuthenticatable)
            : $matcher->fail($this);

        return $foundAuthenticatable;
    }
}
