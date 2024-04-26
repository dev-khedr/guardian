<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Authenticators;

trait HasAuthenticates
{
    protected string $authenticates;

    public function setAuthenticates(string $authenticates): static
    {
        $this->authenticates = $authenticates;

        return $this;
    }

    public function getAuthenticates(): string
    {
        return $this->authenticates;
    }
}
