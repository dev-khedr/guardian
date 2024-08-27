<?php

declare(strict_types=1);

namespace Raid\Guardian\Matchers\Contracts;

use Raid\Guardian\Authenticates\Contracts\Authenticatable;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

interface MatcherInterface
{
    public static function getAttribute(): string;

    public function find(Authenticatable $authenticatable, array $credentials): ?Authenticatable;

    public function fail(AuthenticatorInterface $channel): void;
}
