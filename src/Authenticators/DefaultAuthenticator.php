<?php

declare(strict_types=1);

namespace Raid\Guardian\Authenticators;

use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Authenticators\Contracts\ShouldRunNorms;

class DefaultAuthenticator extends Authenticator implements AuthenticatorInterface, ShouldRunNorms
{
    public const NAME = 'default';
}
