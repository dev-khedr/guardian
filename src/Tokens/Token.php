<?php

declare(strict_types=1);

namespace Raid\Guardian\Tokens;

use DateTimeInterface;
use Raid\Guardian\Tokens\Contracts\TokenInterface;

class Token implements TokenInterface
{
    public function __construct(
        protected string $name = '',
        protected array $abilities = ['*'],
        protected ?DateTimeInterface $expiresAt = null,
    ) {

    }

    public static function new(string $name = '', array $abilities = ['*'], ?DateTimeInterface $expiresAt = null): static
    {
        return new static($name, $abilities, $expiresAt);
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setAbilities(array $abilities): static
    {
        $this->abilities = $abilities;

        return $this;
    }

    public function getAbilities(): array
    {
        return $this->abilities;
    }

    public function setExpiresAt(DateTimeInterface $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    public function getExpiresAt(): ?DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'abilities' => $this->getAbilities(),
            'expiresAt' => $this->getExpiresAt(),
        ];
    }
}
