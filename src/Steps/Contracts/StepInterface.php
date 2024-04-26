<?php

declare(strict_types=1);

namespace Raid\Guardian\Steps\Contracts;

use Raid\Guardian\Channels\Contracts\ChannelInterface;

interface StepInterface
{
    public function handle(ChannelInterface $channel): void;
}
