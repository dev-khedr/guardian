<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Channels;

use Raid\Guardian\Steps\Contracts\ShouldRunQueue;

trait HasSteps
{
    protected array $steps;

    public function setSteps(array $steps): static
    {
        $this->steps = $steps;

        return $this;
    }

    public function getSteps(): array
    {
        return $this->steps;
    }

    protected function getConfiguredSteps(): array
    {
        return isset($this->steps) ?
            $this->getSteps() :
            config('guardian.channel_steps.'.static::class, []);
    }

    protected function runSteps(): void
    {
        foreach ($this->getConfiguredSteps() as $step) {

            $step = app($step);

            $step instanceof ShouldRunQueue ?
                $step->queue($this) :
                $step->handle($this);
        }
    }
}
