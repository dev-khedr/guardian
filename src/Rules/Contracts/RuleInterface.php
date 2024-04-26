<?php

declare(strict_types=1);

namespace Raid\Core\Guardian\Rules\Contracts;

use Raid\Core\Guardian\Channels\Contracts\ChannelInterface;

interface RuleInterface
{
    public function handle(ChannelInterface $channel): bool;
}
