<?php

declare(strict_types=1);

namespace Raid\Guardian\Matchers\Contracts;

use Raid\Guardian\Authenticatable\Contracts\AuthenticatableInterface;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;

interface MatcherInterface
{
    public static function getAttribute(): string;

    public function find(AuthenticatableInterface $authenticatable, array $credentials): ?AuthenticatableInterface;

    public function fail(AuthenticatorInterface $channel): void;
}
