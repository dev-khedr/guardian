<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Authenticators;

use Exception;

trait HasChannels
{
    protected array $channels;

    protected string $defaultChannel;

    public function setChannels(array $channels): static
    {
        $this->channels = $channels;

        return $this;
    }

    public function getChannels(): array
    {
        return $this->channels;
    }

    public function setDefaultChannel(string $channel): static
    {
        $this->defaultChannel = $channel;

        return $this;
    }

    public function getDefaultChannel(): string
    {
        return $this->defaultChannel;
    }

    /**
     * @throws Exception
     */
    protected function getChannel(?string $channel = null): string
    {
        $authenticatorChannel = $channel ?
            $this->getAuthenticatorChannel($channel) :
            $this->getConfiguredDefaultChannel();

        if (! $authenticatorChannel) {
            throw new Exception("Authenticator $channel is not configured for authenticator ".static::class);
        }

        return $authenticatorChannel;
    }

    protected function getAuthenticatorChannel(?string $channel = null): ?string
    {
        foreach ($this->getConfiguredChannels() as $configuredChannel) {
            if ($configuredChannel::getName() !== $channel) {
                continue;
            }

            return $configuredChannel;
        }

        return null;
    }

    protected function getConfiguredChannels(): array
    {
        return isset($this->channels) ?
            $this->getChannels() :
            config('guardian.authenticator_channels.'.static::class, []);
    }

    protected function getConfiguredDefaultChannel(): ?string
    {
        return isset($this->defaultChannel) ?
            $this->getDefaultChannel() :
            config('guardian.default_channel');
    }
}
