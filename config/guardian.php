<?php

use Raid\Guardian\Authenticators\DefaultAuthenticator;
use Raid\Guardian\Norms\MatchingPasswordNorm;
use Raid\Guardian\Matchers\EmailMatcher;

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
