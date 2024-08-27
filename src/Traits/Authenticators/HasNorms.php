<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Authenticators;

trait HasNorms
{
    protected array $norms;

    public function setRules(array $norms): static
    {
        $this->norms = $norms;

        return $this;
    }

    public function getNorms(): array
    {
        return $this->norms;
    }

    protected function getConfiguredNorms()
    {
        return isset($this->norms)
            ? $this->getNorms()
            : config('guardian.authenticator_norms.'.static::class, []);
    }

    protected function runNorms(): bool
    {
        foreach ($this->getConfiguredNorms() as $norm) {

            $normObj = app($norm);

            if ($normObj->handle($this)) {
                continue;
            }

            if (method_exists($normObj, 'fail')) {
                $normObj->fail($this);
            }

            return false;
        }

        return true;
    }
}
