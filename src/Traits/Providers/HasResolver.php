<?php

declare(strict_types=1);

namespace Raid\Guardian\Traits\Providers;

trait HasResolver
{
    protected function registerConfig(): void
    {
        $configResources = glob(__DIR__.'/../../../config/*.php');

        foreach ($configResources as $configPath) {

            $this->publishes([
                $configPath => config_path(basename($configPath)),
            ], 'authentication');

            $this->mergeConfigFrom($configPath, 'authentication');
        }
    }

    protected function registerCommands(): void
    {
        $this->commands($this->commands);
    }
}
