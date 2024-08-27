<?php

declare(strict_types=1);

namespace Raid\Guardian\Authenticators\Contracts;

interface ShouldRunSequences
{
    public function setSteps(array $steps): static;

    public function getSteps(): array;
}
