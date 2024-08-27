<?php

declare(strict_types=1);

namespace Raid\Guardian\Sequences\Contracts;

use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

interface QueueSequenceInterface extends SequenceInterface
{
    public function queue(AuthenticatorInterface $channel): void;
}
