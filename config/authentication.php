<?php

use Raid\Core\Guardian\Channels\DefaultChannel;
use Raid\Core\Guardian\Rules\MatchingPasswordRule;
use Raid\Core\Guardian\Workers\EmailWorker;

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
