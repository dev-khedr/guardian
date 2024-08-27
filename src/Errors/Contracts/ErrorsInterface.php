<?php

declare(strict_types=1);

namespace Raid\Guardian\Errors\Contracts;

interface ErrorsInterface
{
    public function add(string $key, string $message): void;

    public function has(string $key): bool;

    public function get(string $key): array;

    public function any(): bool;

    public function first(?string $key = null): ?string;

    public function last(?string $key = null): ?string;

    public function toArray(): array;

    public function toJson(int $options = JSON_ERROR_NONE): string;
}
