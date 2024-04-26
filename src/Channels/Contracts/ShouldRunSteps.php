<?php

declare(strict_types=1);

namespace Raid\Guardian\Channels\Contracts;

interface ShouldRunSteps
{
    public function setSteps(array $steps): static;

    public function getSteps(): array;
}
