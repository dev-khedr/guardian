<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Authenticators;

use Illuminate\Support\Arr;

trait HasCredentials
{
    protected array $credentials;

    protected function setCredentials(array $credentials): void
    {
        $this->credentials = $credentials;
    }

    public function getCredentials(?string $key = null, mixed $default = null): mixed
    {
        return $key
            ? Arr::get($this->credentials, $key, $default)
            : $this->credentials;
    }
}
