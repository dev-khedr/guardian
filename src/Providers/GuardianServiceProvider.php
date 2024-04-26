<?php

declare(strict_types=1);

namespace Raid\Guardian\Providers;

use Illuminate\Support\ServiceProvider;
use Raid\Guardian\Commands\MakeAuthenticator;
use Raid\Guardian\Commands\MakeChannel;
use Raid\Guardian\Commands\MakeRule;
use Raid\Guardian\Commands\MakeStep;
use Raid\Guardian\Commands\MakeWorker;
use Raid\Guardian\Traits\Providers\HasResolver;

class GuardianServiceProvider extends ServiceProvider
{
    use HasResolver;

    protected array $commands = [
        MakeAuthenticator::class,
        MakeChannel::class,
        MakeRule::class,
        MakeStep::class,
        MakeWorker::class,
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
