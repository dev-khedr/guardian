<?php

declare(strict_types=1);

namespace Raid\Guardian\Workers\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Raid\Guardian\Authenticates\Contracts\Authenticates;
use Raid\Guardian\Channels\Contracts\ChannelInterface;

interface WorkerInterface
{
    public static function getAttribute(): string;

    public function find(Authenticates $authenticates, array $credentials): ?Authenticatable;

    public function fail(ChannelInterface $channel): void;
}
