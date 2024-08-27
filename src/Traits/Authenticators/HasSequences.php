<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Authenticators;

use Raid\Guardian\Sequences\Contracts\QueueSequenceInterface;

trait HasSequences
{
    protected array $sequences;

    public function setSequences(array $sequences): static
    {
        $this->sequences = $sequences;

        return $this;
    }

    public function getSequences(): array
    {
        return $this->sequences;
    }

    protected function getConfiguredSequences(): array
    {
        return isset($this->sequences)
            ? $this->getSequences()
            : config('guardian.authenticator_sequences.'.static::class, []);
    }

    protected function runSequences(): void
    {
        foreach ($this->getConfiguredSequences() as $sequence) {

            $sequenceObj = app($sequence);

            $sequenceObj instanceof QueueSequenceInterface
                ? $sequenceObj->queue($this)
                : $sequenceObj->handle($this);
        }
    }
}
