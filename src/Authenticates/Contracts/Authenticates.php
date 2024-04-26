<?php

declare(strict_types=1);

namespace Raid\Guardian\Authenticates\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface Authenticates
{
    public function findForAuthentication(string $attribute, mixed $value): ?Authenticatable;
}
