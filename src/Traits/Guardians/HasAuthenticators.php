<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Guardians;

use Exception;

trait HasAuthenticators
{
    protected array $authenticators;

    protected string $defaultAuthenticator;

    public function setAuthenticators(array $authenticators): static
    {
        $this->authenticators = $authenticators;

        return $this;
    }

    public function getAuthenticators(): array
    {
        return $this->authenticators;
    }

    public function setDefaultAuthenticator(string $authenticator): static
    {
        $this->defaultAuthenticator = $authenticator;

        return $this;
    }

    public function getDefaultAuthenticator(): string
    {
        return $this->defaultAuthenticator;
    }

    /**
     * @throws Exception
     */
    protected function getAuthenticator(?string $authenticator = null): string
    {
        $guardianAuthenticator = $authenticator
            ? $this->getGuardianAuthenticator($authenticator)
            : $this->getConfiguredDefaultAuthenticator();

        if (! $guardianAuthenticator) {
            throw new Exception("Authenticator $authenticator is not configured for guardian ".static::class);
        }

        return $guardianAuthenticator;
    }

    protected function getGuardianAuthenticator(?string $authenticator = null): ?string
    {
        foreach ($this->getConfiguredAuthenticators() as $configuredAuthenticator) {
            if ($configuredAuthenticator::getName() !== $authenticator) {
                continue;
            }

            return $configuredAuthenticator;
        }

        return null;
    }

    protected function getConfiguredAuthenticators(): array
    {
        return isset($this->authenticators)
            ? $this->getAuthenticators()
            : config('guardian.guardian_authenticators.'.static::class, []);
    }

    protected function getConfiguredDefaultAuthenticator(): ?string
    {
        return isset($this->defaultAuthenticator)
            ? $this->getDefaultAuthenticator()
            : config('guardian.default_authenticator');
    }
}
