<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Steps;

use DateInterval;
use DateTimeInterface;
use Raid\Guardian\Channels\Contracts\ChannelInterface;
use Raid\Guardian\Jobs\StepJob;

trait HasQueue
{
    protected function getJob(): string
    {
        return StepJob::class;
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

    public function queue(ChannelInterface $channel): void
    {
        $job = $this->getJob()::dispatch($this, $channel);

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
