<?php

declare(strict_types=1);

namespace Raid\Guardian\Channels\Contracts;

interface ShouldRunRules
{
    public function setRules(array $rules): static;

    public function getRules(): array;
}
