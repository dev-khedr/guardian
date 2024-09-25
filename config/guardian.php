<?php

use Raid\Guardian\Authenticators\DefaultAuthenticator;
use Raid\Guardian\Matchers\EmailMatcher;
use Raid\Guardian\Norms\MatchingPasswordNorm;

return [

    'default_authenticator' => DefaultAuthenticator::class,

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
