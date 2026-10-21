<?php

declare(strict_types=1);

namespace RavindraDot\ModelIntegrity;

use Illuminate\Support\ServiceProvider;
use RavindraDot\ModelIntegrity\Console\CheckCommand;

final class ModelIntegrityServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([CheckCommand::class]);
        }
    }
}
