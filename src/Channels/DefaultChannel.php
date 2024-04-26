<?php

declare(strict_types=1);

namespace Raid\Core\Guardian\Channels;

use Raid\Core\Guardian\Channels\Contracts\ChannelInterface;
use Raid\Core\Guardian\Channels\Contracts\ShouldRunRules;

class DefaultChannel extends Channel implements ChannelInterface, ShouldRunRules
{
    public const NAME = 'default';
}
