<?php

declare(strict_types=1);

namespace RavindraDot\ModelIntegrity\Tests\Feature;

use Orchestra\Testbench\TestCase;
use RavindraDot\ModelIntegrity\ModelIntegrityServiceProvider;

final class CommandTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [ModelIntegrityServiceProvider::class];
    }

    public function test_command_is_registered_and_runs(): void
    {
        $this->artisan('model-integrity:check')
            ->expectsOutput('Result: 0 finding(s)')
            ->assertExitCode(0);
    }

    public function test_command_reports_a_missing_model_and_fails(): void
    {
        $this->app->setBasePath(__DIR__.'/../Fixtures/missing-model');

        $this->artisan('model-integrity:check')
            ->expectsOutputToContain('factory.model_not_found')
            ->expectsOutput('Result: 1 finding(s)')
            ->assertExitCode(1);
    }
}
