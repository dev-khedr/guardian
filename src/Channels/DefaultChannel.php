<?php

declare(strict_types=1);

namespace Raid\Guardian\Channels;

use Raid\Guardian\Channels\Contracts\ChannelInterface;
use Raid\Guardian\Channels\Contracts\ShouldRunRules;

class DefaultChannel extends Channel implements ChannelInterface, ShouldRunRules
{
    public const NAME = 'default';
}
