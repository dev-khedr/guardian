<?php

declare(strict_types=1);

namespace Raid\Core\Authentication\Channels\Contracts;

interface ShouldRunRules
{
    public function setRules(array $rules): static;

    public function getRules(): array;
}
