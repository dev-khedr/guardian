<?php

declare(strict_types=1);

namespace Raid\Guardian\Authenticates\Contracts;

use Illuminate\Contracts\Auth\Authenticatable as IlluminateAuthenticatable;

interface Authenticatable extends IlluminateAuthenticatable
{
    public function findForAuthentication(string $attribute, mixed $value): ?Authenticatable;
}
