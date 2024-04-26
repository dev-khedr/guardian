<?php

use Raid\Core\Authentication\Channels\DefaultChannel;
use Raid\Core\Authentication\Rules\MatchingPasswordRule;
use Raid\Core\Authentication\Workers\EmailWorker;

return [

    'default_channel' => DefaultChannel::class,

    'authenticator_channels' => [],

    'channel_workers' => [
        DefaultChannel::class => [
            EmailWorker::class,
        ],
    ],

    'channel_rules' => [
        DefaultChannel::class => [
            MatchingPasswordRule::class,
        ],
    ],

    'channel_steps' => [],

];
