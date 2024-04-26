<?php

declare(strict_types=1);

namespace Raid\Core\Guardian\Traits\Channels;

trait HasRules
{
    protected array $rules;

    public function setRules(array $rules): static
    {
        $this->rules = $rules;

        return $this;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    protected function getConfiguredRules()
    {
        return isset($this->rules) ?
            $this->getRules() :
            config('authentication.channel_rules.'.static::class, []);
    }

    protected function runRules(): bool
    {
        foreach ($this->getConfiguredRules() as $rule) {

            $rule = app($rule);

            if ($rule->handle($this)) {
                continue;
            }

            if (method_exists($rule, 'fail')) {
                $rule->fail($this);
            }

            return false;
        }

        return true;
    }
}
