<?php

declare(strict_types=1);

namespace Raid\Core\Authentication\Workers\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Raid\Core\Authentication\Authenticates\Contracts\Authenticates;

interface WorkerInterface
{
    public static function getAttribute(): string;

    public function find(Authenticates $authenticates, array $credentials): ?Authenticatable;
}
