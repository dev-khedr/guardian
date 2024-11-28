<?php

declare(strict_types=1);

namespace Raid\Guardian\Authenticators\Contracts;

use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;
use Raid\Guardian\Errors\Contracts\ErrorsInterface;
use Raid\Guardian\Matchers\Contracts\MatcherInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;

interface AuthenticatorInterface
{
    public static function new(): static;

    public static function getName(): string;

    public function attempt(AuthenticatableInterface $authenticatable, array $credentials, ?TokenInterface $token = null): static;

    public function login(AuthenticatableInterface $authenticatable, ?TokenInterface $token = null): static;

    public function getAuthenticatable(?string $key = null, mixed $default = null): mixed;

    public function isAuthenticated(): bool;

    public function getCredentials(?string $key = null, mixed $default = null): mixed;

    public function errors(): ErrorsInterface;

    public function fail(string $key = 'error', string $message = ''): void;

    public function failed(): bool;

    public function getToken(): string;

    public function setMatchers(array $matchers): static;

    public function getMatchers(): array;

    public function getMatcher(): MatcherInterface;
}
