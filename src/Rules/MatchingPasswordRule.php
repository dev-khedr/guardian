<?php

namespace Raid\Core\Authentication\Rules;

use Illuminate\Support\Facades\Hash;
use Raid\Core\Authentication\Channels\Contracts\ChannelInterface;
use Raid\Core\Authentication\Rules\Contracts\RuleInterface;

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
