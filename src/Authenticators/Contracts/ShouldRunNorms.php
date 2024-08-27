<?php

declare(strict_types=1);

namespace Raid\Guardian\Authenticators\Contracts;

interface ShouldRunNorms
{
    public function setRules(array $rules): static;

    public function getRules(): array;
}
