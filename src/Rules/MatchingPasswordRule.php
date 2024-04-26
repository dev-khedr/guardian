<?php

namespace Raid\Core\Guardian\Rules;

use Illuminate\Support\Facades\Hash;
use Raid\Core\Guardian\Channels\Contracts\ChannelInterface;
use Raid\Core\Guardian\Rules\Contracts\RuleInterface;

class MatchingPasswordRule implements RuleInterface
{
    public function handle(ChannelInterface $channel): bool
    {
        return Hash::check(
            $channel->getCredentials('password'),
            $channel->getAuthenticatable()->getAuthPassword(),
        );
    }

    public function fail(ChannelInterface $channel): void
    {
        $channel->fail(message: __('auth.failed'));
    }
}
