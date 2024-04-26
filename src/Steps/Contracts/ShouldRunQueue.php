<?php

declare(strict_types=1);

namespace Raid\Core\Guardian\Steps\Contracts;

use Raid\Core\Guardian\Channels\Contracts\ChannelInterface;

interface ShouldRunQueue
{
    public function queue(ChannelInterface $channel): void;
}
