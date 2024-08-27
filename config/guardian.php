<?php

use Raid\Guardian\Authenticators\DefaultAuthenticator;
use Raid\Guardian\Norms\MatchingPasswordNorm;
use Raid\Guardian\Matchers\EmailMatcher;

return [

    'default_channel' => DefaultAuthenticator::class,

    'authenticator_channels' => [],

    'channel_workers' => [
        DefaultAuthenticator::class => [
            EmailMatcher::class,
        ],
    ],

    'channel_rules' => [
        DefaultAuthenticator::class => [
            MatchingPasswordNorm::class,
        ],
    ],

    'channel_steps' => [],

];
