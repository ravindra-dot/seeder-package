<?php

declare(strict_types=1);

namespace ModelIntegrity;

use Illuminate\Support\ServiceProvider;
use ModelIntegrity\Console\CheckCommand;

final class ModelIntegrityServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([CheckCommand::class]);
        }
    }
}
