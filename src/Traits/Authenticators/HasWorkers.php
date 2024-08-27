<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Authenticators;

use Illuminate\Contracts\Auth\Authenticatable;
use Raid\Guardian\Authenticates\Contracts\Authenticatable;
use Raid\Guardian\Matchers\Contracts\MatcherInterface;

trait HasWorkers
{
    protected array $workers;

    protected MatcherInterface $worker;

    public function setWorkers(array $workers): static
    {
        $this->workers = $workers;

        return $this;
    }

    public function getWorkers(): array
    {
        return $this->workers;
    }

    protected function setWorker(MatcherInterface $worker): void
    {
        $this->worker = $worker;
    }

    public function getWorker(): MatcherInterface
    {
        return $this->worker;
    }

    protected function getConfiguredWorkers(): array
    {
        return isset($this->workers) ?
            $this->getWorkers() :
            config('guardian.channel_workers.'.static::class, []);
    }

    protected function findAuthenticatable(Authenticatable $authenticates, array $credentials): ?Authenticatable
    {
        $worker = $this->getChannelWorker($credentials);

        return $worker ?
            $this->findWorkerAuthenticatable($worker, $authenticates, $credentials) :
            null;
    }

    protected function getChannelWorker(array $credentials): ?MatcherInterface
    {
        $worker = $this->getWorkerForCredentials($credentials);

        $worker ?
            $this->setWorker($worker) :
            $this->fail(message: __('auth.worker_not_found'));

        return $worker;
    }

    protected function getWorkerForCredentials(array $credentials): ?MatcherInterface
    {
        foreach ($this->getConfiguredWorkers() as $worker) {
            if (! array_key_exists($worker::getAttribute(), $credentials)) {
                continue;
            }

            return app($worker);
        }

        return null;
    }

    protected function findWorkerAuthenticatable(MatcherInterface $worker, Authenticatable $authenticates, array $credentials): ?Authenticatable
    {
        $authenticatable = $worker->find($authenticates, $credentials);

        $authenticatable ?
            $this->setAuthenticatable($authenticatable) :
            $worker->fail($this);

        return $authenticatable;
    }
}
