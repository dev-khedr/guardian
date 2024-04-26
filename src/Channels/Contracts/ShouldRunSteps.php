<?php

declare(strict_types=1);

namespace Raid\Core\Authentication\Channels\Contracts;

interface ShouldRunSteps
{
    public function setSteps(array $steps): static;

    public function getSteps(): array;
}
