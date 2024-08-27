<?php

namespace Raid\Guardian\Norms;

use Illuminate\Support\Facades\Hash;
use Raid\Guardian\Authenticators\Contracts\AuthenticatorInterface;
use Raid\Guardian\Norms\Contracts\NormInterface;

class MatchingPasswordNorm implements NormInterface
{
    public function handle(AuthenticatorInterface $channel): bool
    {
        return Hash::check(
            $channel->getCredentials('password'),
            $channel->getAuthenticatable()->getAuthPassword(),
        );
    }

    public function fail(AuthenticatorInterface $channel): void
    {
        $channel->fail(message: __('auth.failed'));
    }
}
