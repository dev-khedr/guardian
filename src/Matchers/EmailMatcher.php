<?php

declare(strict_types=1);

namespace Raid\Guardian\Matchers;

use Raid\Guardian\Matchers\Contracts\MatcherInterface;

class EmailMatcher extends Matcher implements MatcherInterface
{
    public const ATTRIBUTE = 'email';
}
