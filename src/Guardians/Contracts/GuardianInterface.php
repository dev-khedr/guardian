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

    public function attempt(array $credentials, ?string $channel = null, ?TokenInterface $token = null): AuthenticatorInterface;

    public function login(Authenticatable $authenticatable, ?string $channel = null, ?TokenInterface $token = null): AuthenticatorInterface;

    public function setAuthenticates(string $authenticates): static;

    public function getAuthenticates(): string;

    public function setChannels(array $channels): static;

    public function getChannels(): array;

    public function setDefaultChannel(string $channel): static;

    public function getDefaultChannel(): string;
}
