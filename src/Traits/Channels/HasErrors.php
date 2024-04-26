<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Channels;

use Raid\Guardian\Errors\Contracts\ErrorsInterface;
use Raid\Guardian\Errors\Errors;

trait HasErrors
{
    protected Errors $errors;

    public function errors(): ErrorsInterface
    {
        if (! isset($this->errors)) {
            $this->errors = app(Errors::class);
        }

        return $this->errors;
    }

    public function fail(string $key = 'error', string $message = ''): void
    {
        $this->errors()->add($key, $message);
    }

    public function failed(): bool
    {
        return $this->errors()->any();
    }
}
