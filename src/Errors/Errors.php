<?php

declare(strict_types=1);

namespace Raid\Core\Guardian\Errors;

use Raid\Core\Guardian\Errors\Contracts\ErrorsInterface;

class Errors implements ErrorsInterface
{
    protected array $errors = [];

    public function add(string $key, string $message): void
    {
        $this->errors[$key][] = $message;
    }

    public function has(string $key): bool
    {
        return isset($this->errors[$key]);
    }

    public function get(string $key): array
    {
        return $this->errors[$key] ?? [];
    }

    public function any(): bool
    {
        return ! empty($this->errors);
    }

    public function first(?string $key = null): ?string
    {
        $key = $key ?? array_key_first($this->errors);

        return $key && $this->has($key) ?
            $this->firstError($key) :
            null;
    }

    public function last(?string $key = null): ?string
    {
        $key = $key ?? array_key_last($this->errors);

        return $key && $this->has($key) ?
            $this->lastError($key) :
            null;
    }

    protected function firstError(string $key): ?string
    {
        return $this->errors[$key][0];
    }

    protected function lastError(string $key): ?string
    {
        return $this->errors[$key][count($this->errors[$key]) - 1];
    }

    public function toArray(): array
    {
        return $this->errors;
    }

    public function toJson(int $options = JSON_ERROR_NONE): string
    {
        return json_encode($this->errors, $options);
    }
}
