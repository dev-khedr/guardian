<?php

declare(strict_types=1);

namespace Raid\Core\Authentication\Channels;

use Raid\Core\Authentication\Channels\Contracts\ChannelInterface;
use Raid\Core\Authentication\Channels\Contracts\ShouldRunRules;

class DefaultChannel extends Channel implements ChannelInterface, ShouldRunRules
{
    public const NAME = 'default';
}
