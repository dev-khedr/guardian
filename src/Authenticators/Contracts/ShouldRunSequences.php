<?php

declare(strict_types=1);

namespace Raid\Guardian\Authenticators\Contracts;

interface ShouldRunSequences
{
    public function setSequences(array $sequences): static;

    public function getSequences(): array;
}
