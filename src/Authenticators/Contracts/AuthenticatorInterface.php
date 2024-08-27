<?php

declare(strict_types=1);

namespace Raid\Guardian\Authenticators\Contracts;

use Raid\Guardian\Authenticates\Contracts\Authenticatable;
use Raid\Guardian\Errors\Contracts\ErrorsInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;
use Raid\Guardian\Matchers\Contracts\MatcherInterface;

interface AuthenticatorInterface
{
    public static function new(): static;

    public static function getName(): string;

    public function attempt(Authenticatable $authenticatable, array $credentials, ?TokenInterface $token = null): static;

    public function login(Authenticatable $authenticatable, ?TokenInterface $token = null): static;

    public function getAuthenticatable(?string $key = null, mixed $default = null): mixed;

    public function isAuthenticated(): bool;

    public function getCredentials(?string $key = null, mixed $default = null): mixed;

    public function errors(): ErrorsInterface;

    public function fail(string $key = 'error', string $message = ''): void;

    public function failed(): bool;

    public function getToken(?string $key = null, mixed $default = null): mixed;

    public function getStringToken(): string;

    public function setWorkers(array $workers): static;

    public function getWorkers(): array;

    public function getWorker(): MatcherInterface;
}
