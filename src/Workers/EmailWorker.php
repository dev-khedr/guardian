<?php

declare(strict_types=1);

namespace Raid\Guardian\Workers;

use Raid\Guardian\Workers\Contracts\WorkerInterface;

class EmailWorker extends Worker implements WorkerInterface
{
    public const ATTRIBUTE = 'email';
}
