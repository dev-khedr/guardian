<?php

declare(strict_types=1);

namespace Raid\Guardian\Guardians\Contracts;

use Raid\Guardian\Authenticates\Contracts\Authenticatable;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;

interface GuardianInterface
{
    public static function new(): static;

    public static function getName(): string;

    public function attempt(array $credentials, ?string $authenticator = null, ?TokenInterface $token = null): AuthenticatorInterface;

    public function login(Authenticatable $authenticatable, ?string $authenticator = null, ?TokenInterface $token = null): AuthenticatorInterface;

    public function setAuthenticatable(string $authenticatable): static;

    public function getAuthenticatable(): string;

    public function setAuthenticators(array $authenticators): static;

    public function getAuthenticators(): array;

    public function setDefaultAuthenticator(string $authenticator): static;

    public function getDefaultAuthenticator(): string;
}
