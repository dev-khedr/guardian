<?php

declare(strict_types=1);

namespace Raid\Core\Guardian\Workers;

use Raid\Core\Guardian\Workers\Contracts\WorkerInterface;

class EmailWorker extends Worker implements WorkerInterface
{
    public const ATTRIBUTE = 'email';
}
