<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Guardians;

trait HasAuthenticatable
{
    protected string $authenticatable;

    public function setAuthenticatable(string $authenticatable): static
    {
        $this->authenticatable = $authenticatable;

        return $this;
    }

    public function getAuthenticatable(): string
    {
        return $this->authenticatable;
    }
}
