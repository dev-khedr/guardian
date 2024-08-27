<?php

declare(strict_types=1);

namespace Raid\Guardian\Sequences\Contracts;

use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

interface SequenceInterface
{
    public function handle(AuthenticatorInterface $channel): void;
}
