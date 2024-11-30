<?php

namespace Raid\Guardian\Drivers\Contracts;

use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;

interface DriverInterface
{
    public function generateToken(AuthenticatableInterface $authenticatable, ?TokenInterface $token = null): string;
}
