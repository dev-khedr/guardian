<?php

declare(strict_types=1);

namespace Raid\Core\Authentication\Steps\Contracts;

use Raid\Core\Authentication\Channels\Contracts\ChannelInterface;

interface ShouldRunQueue
{
    public function queue(ChannelInterface $channel): void;
}
