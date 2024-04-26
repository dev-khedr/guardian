<?php

declare(strict_types=1);

namespace Raid\Guardian\Channels\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Raid\Guardian\Authenticates\Contracts\Authenticates;
use Raid\Guardian\Errors\Contracts\ErrorsInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;
use Raid\Guardian\Workers\Contracts\WorkerInterface;

interface ChannelInterface
{
    public static function new(): static;

    public static function getName(): string;

    public function attempt(Authenticates $authenticates, array $credentials, ?TokenInterface $token = null): static;

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

    public function getWorker(): WorkerInterface;
}
