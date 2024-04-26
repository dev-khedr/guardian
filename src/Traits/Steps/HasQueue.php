<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Steps;

use DateInterval;
use DateTimeInterface;
use Illuminate\Foundation\Bus\PendingDispatch;
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
        $this->processJob(
            $this->getJob()::dispatch($this, $channel),
        );
    }

    protected function processJob(PendingDispatch $job): void
    {
        $this->processConnection($job, $this->getConnection());
        $this->processQueue($job, $this->getQueue());
        $this->processDelay($job, $this->getDelay());
    }

    protected function processConnection(PendingDispatch $job, ?string $connection): void
    {
        if (! $connection) {
            return;
        }

        $job->onConnection($connection);
    }

    protected function processQueue(PendingDispatch $job, ?string $queue): void
    {
        if (! $queue) {
            return;
        }

        $job->onQueue($queue);
    }

    protected function processDelay(PendingDispatch $job, DateInterval|DateTimeInterface|int|null $delay): void
    {
        if (! $delay) {
            return;
        }

        $job->delay($delay);
    }
}
