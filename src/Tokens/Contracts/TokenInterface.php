<?php

declare(strict_types=1);

namespace Raid\Core\Authentication\Tokens\Contracts;

use DateTimeInterface;

interface TokenInterface
{
    public static function new(string $name = '', array $abilities = ['*'], ?DateTimeInterface $expiresAt = null): static;

    public function setName(string $name): static;

    public function getName(): string;

    public function setAbilities(array $abilities): static;

    public function getAbilities(): array;

    public function setExpiresAt(DateTimeInterface $expiresAt): static;

    public function getExpiresAt(): ?DateTimeInterface;

    public function toArray(): array;
}
