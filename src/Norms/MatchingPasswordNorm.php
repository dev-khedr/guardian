<?php

namespace Raid\Guardian\Norms;

use Illuminate\Support\Facades\Hash;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Norms\Contracts\NormInterface;

class MatchingPasswordNorm implements NormInterface
{
    public function handle(AuthenticatorInterface $authenticator): bool
    {
        return Hash::check(
            $authenticator->getCredentials('password'),
            $authenticator->getAuthenticatable()->getAuthPassword(),
        );
    }

    public function fail(AuthenticatorInterface $authenticator): void
    {
        $authenticator->fail(message: __('auth.failed'));
    }
}
