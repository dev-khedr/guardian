<?php

declare(strict_types=1);

namespace Raid\Guardian\Rules\Contracts;

use Raid\Guardian\Channels\Contracts\ChannelInterface;

interface RuleInterface
{
    public function handle(ChannelInterface $channel): bool;
}
