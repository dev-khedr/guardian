<?php

declare(strict_types=1);

namespace Raid\Guardian\Providers;

use Illuminate\Support\ServiceProvider;
use Raid\Guardian\Commands\MakeAuthenticator;
use Raid\Guardian\Commands\MakeDriver;
use Raid\Guardian\Commands\MakeGuardian;
use Raid\Guardian\Commands\MakeMatcher;
use Raid\Guardian\Commands\MakeNorm;
use Raid\Guardian\Commands\MakeSequence;
use Raid\Guardian\Traits\Providers\HasResolver;

class GuardianServiceProvider extends ServiceProvider
{
    use HasResolver;

    protected array $commands = [
        MakeAuthenticator::class,
        MakeDriver::class,
        MakeGuardian::class,
        MakeMatcher::class,
        MakeNorm::class,
        MakeSequence::class,
    ];

    public function register(): void
    {
        $this->registerConfig();
    }

    public function boot(): void
    {
        $this->registerCommands();
        $this->registerDriver();
    }
}
