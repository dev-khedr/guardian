<?php

declare(strict_types=1);

namespace Raid\Guardian\Providers;

use Illuminate\Support\ServiceProvider;
use Raid\Guardian\Commands\MakeAuthenticator;
use Raid\Guardian\Commands\MakeGuardian;
use Raid\Guardian\Commands\MakeMatcher;
use Raid\Guardian\Commands\MakeNorm;
use Raid\Guardian\Commands\MakeSequence;
use Raid\Guardian\Traits\Providers\HasResolver;

class GuardianServiceProvider extends ServiceProvider
{
    use HasResolver;

    protected array $commands = [
        MakeGuardian::class,
        MakeAuthenticator::class,
        MakeNorm::class,
        MakeSequence::class,
        MakeMatcher::class,
    ];

    public function register(): void
    {
        $this->registerConfig();
    }

    public function boot(): void
    {
        $this->registerCommands();
    }
}
