<?php

declare(strict_types=1);

namespace Raid\Core\Guardian\Providers;

use Illuminate\Support\ServiceProvider;
use Raid\Core\Guardian\Commands\MakeAuthenticator;
use Raid\Core\Guardian\Commands\MakeChannel;
use Raid\Core\Guardian\Commands\MakeRule;
use Raid\Core\Guardian\Commands\MakeStep;
use Raid\Core\Guardian\Commands\MakeWorker;
use Raid\Core\Guardian\Traits\Providers\HasResolver;

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
