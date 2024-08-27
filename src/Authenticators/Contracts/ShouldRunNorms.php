<?php

declare(strict_types=1);

namespace Raid\Guardian\Authenticators\Contracts;

interface ShouldRunNorms
{
    public function setNorms(array $norms): static;

    public function getNorms(): array;
}
