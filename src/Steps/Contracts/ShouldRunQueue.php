<?php

declare(strict_types=1);

namespace Raid\Guardian\Steps\Contracts;

use Raid\Guardian\Channels\Contracts\ChannelInterface;

interface ShouldRunQueue
{
    public function queue(ChannelInterface $channel): void;
}
