<?php

declare(strict_types=1);

namespace Raid\Core\Authentication\Workers;

use Raid\Core\Authentication\Workers\Contracts\WorkerInterface;

class EmailWorker extends Worker implements WorkerInterface
{
    public const ATTRIBUTE = 'email';
}
