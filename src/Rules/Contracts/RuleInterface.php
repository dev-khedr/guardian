<?php

declare(strict_types=1);

namespace Raid\Core\Authentication\Rules\Contracts;

use Raid\Core\Authentication\Channels\Contracts\ChannelInterface;

interface RuleInterface
{
    public function handle(ChannelInterface $channel): bool;
}
