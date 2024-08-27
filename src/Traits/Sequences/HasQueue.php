<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Sequences;

use DateInterval;
use DateTimeInterface;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Jobs\SequenceJob;

trait HasQueue
{
    protected function getJob(): string
    {
        return SequenceJob::class;
    }

    protected function getConnection(): ?string
    {
        return null;
    }

    protected function getQueue(): ?string
    {
        return null;
    }

    protected function getDelay(): DateInterval|DateTimeInterface|int|null
    {
        return null;
    }

    public function queue(AuthenticatorInterface $authenticator): void
    {
        $job = $this->getJob()::dispatch($this, $authenticator);

        if ($connection = $this->getConnection()) {
            $job->onConnection($connection);
        }

        if ($queue = $this->getQueue()) {
            $job->onQueue($queue);
        }

        if ($delay = $this->getDelay()) {
            $job->delay($delay);
        }
    }
}
