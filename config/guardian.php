<?php

use Raid\Guardian\Authenticators\DefaultAuthenticator;
use Raid\Guardian\Drivers\SanctumDriver;
use Raid\Guardian\Matchers\EmailMatcher;
use Raid\Guardian\Norms\MatchingPasswordNorm;

return [

    'default_authenticator' => DefaultAuthenticator::class,

    'default_driver' => SanctumDriver::class,

    'guardian_authenticators' => [],

    'authenticator_matchers' => [
        DefaultAuthenticator::class => [
            EmailMatcher::class,
        ],
    ],

    'authenticator_norms' => [
        DefaultAuthenticator::class => [
            MatchingPasswordNorm::class,
        ],
    ],

    'authenticator_sequences' => [],

];
