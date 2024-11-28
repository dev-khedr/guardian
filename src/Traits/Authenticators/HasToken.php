<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Authenticators;

use Illuminate\Support\Arr;
use Laravel\Sanctum\NewAccessToken;

trait HasToken
{
    protected string $token;

    protected function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
