<?php

use Raid\Guardian\Channels\DefaultChannel;
use Raid\Guardian\Rules\MatchingPasswordRule;
use Raid\Guardian\Workers\EmailWorker;

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
