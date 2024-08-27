<?php

declare(strict_types=1);

namespace Raid\Guardian\Authenticatable\Contracts;

use Illuminate\Contracts\Auth\Authenticatable as IlluminateAuthenticatable;

interface AuthenticatableInterface extends IlluminateAuthenticatable
{
    public function findForAuthentication(string $attribute, mixed $value): ?AuthenticatableInterface;
}
