<?php

declare(strict_types=1);

namespace Raid\Core\Guardian\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Raid\Core\Guardian\Channels\Contracts\ChannelInterface;
use Raid\Core\Guardian\Steps\Contracts\StepInterface;

class StepJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly StepInterface $step,
        private readonly ChannelInterface $channel,
    ) {

    }

    public function handle(): void
    {
        $this->step->handle($this->channel);
    }
}
