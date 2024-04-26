<?php

declare(strict_types=1);

namespace Raid\Core\Authentication\Providers;

use Illuminate\Support\ServiceProvider;
use Raid\Core\Authentication\Commands\MakeAuthenticator;
use Raid\Core\Authentication\Commands\MakeChannel;
use Raid\Core\Authentication\Commands\MakeRule;
use Raid\Core\Authentication\Commands\MakeStep;
use Raid\Core\Authentication\Commands\MakeWorker;
use Raid\Core\Authentication\Traits\Providers\HasResolver;

class AuthenticationServiceProvider extends ServiceProvider
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
