<?php

declare(strict_types=1);

namespace Raid\Guardian\Norms\Contracts;

use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

interface NormInterface
{
    public function handle(AuthenticatorInterface $channel): bool;
}
