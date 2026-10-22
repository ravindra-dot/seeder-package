<?php

declare(strict_types=1);

namespace ModelIntegrity\Tests\Feature;

use ModelIntegrity\ModelIntegrityServiceProvider;
use Orchestra\Testbench\TestCase;

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

    public function test_command_reports_a_wrong_import_in_the_injected_fixture(): void
    {
        $this->app->setBasePath(__DIR__.'/../Fixtures/injected-failures');

        $this->artisan('model-integrity:check')
            ->expectsOutputToContain('App\\Billing\\Customer')
            ->expectsOutput('Result: 1 finding(s)')
            ->assertExitCode(1);
    }

    public function test_command_shows_conventional_mapping_warning_without_failing(): void
    {
        $this->app->setBasePath(__DIR__.'/../Fixtures/conventional-mapping');

        $this->artisan('model-integrity:check')
            ->expectsOutputToContain('factory.model_mapping_suspicious')
            ->expectsOutput('Result: 1 finding(s)')
            ->assertExitCode(0);
    }

    public function test_command_reports_inherited_model_mapping_warnings_without_failing(): void
    {
        $this->app->setBasePath(__DIR__.'/../Fixtures/inherited-mapping');

        $this->artisan('model-integrity:check')
            ->expectsOutputToContain('App\\Models\\Author')
            ->expectsOutputToContain('App\\Models\\AttributeChild')
            ->expectsOutput('Result: 2 finding(s)')
            ->assertExitCode(0);
    }
}
