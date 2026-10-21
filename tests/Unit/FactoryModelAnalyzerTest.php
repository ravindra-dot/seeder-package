<?php

declare(strict_types=1);

namespace RavindraDot\ModelIntegrity\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RavindraDot\ModelIntegrity\Analysis\FactoryModelAnalyzer;

final class FactoryModelAnalyzerTest extends TestCase
{
    public function test_valid_explicit_mapping_and_duplicate_short_names_do_not_create_findings(): void
    {
        $report = (new FactoryModelAnalyzer)->analyze(__DIR__.'/../Fixtures/valid-project');

        self::assertSame([], $report->findings());
        self::assertFalse($report->hasErrors());
    }

    public function test_missing_explicit_model_is_reported_with_location(): void
    {
        $report = (new FactoryModelAnalyzer)->analyze(__DIR__.'/../Fixtures/missing-model');

        self::assertCount(1, $report->findings());
        $finding = $report->findings()[0];
        self::assertSame('factory.model_not_found', $finding->rule);
        self::assertSame('error', $finding->severity);
        self::assertSame('database/factories/UserFactory.php', $finding->file);
        self::assertSame(10, $finding->line);
        self::assertStringContainsString('App\\Models\\Missing', $finding->message);
        self::assertTrue($report->hasErrors());
    }

    public function test_parse_error_does_not_turn_an_incomplete_index_into_a_missing_model_error(): void
    {
        $report = (new FactoryModelAnalyzer)->analyze(__DIR__.'/../Fixtures/parse-error');

        self::assertCount(1, $report->findings());
        self::assertSame('analysis.parse_error', $report->findings()[0]->rule);
        self::assertFalse($report->hasErrors());
    }

    public function test_wrong_import_is_detected_while_convention_only_factory_is_not_reported(): void
    {
        $report = (new FactoryModelAnalyzer)->analyze(__DIR__.'/../Fixtures/injected-failures');

        self::assertCount(1, $report->findings());
        $finding = $report->findings()[0];
        self::assertSame('factory.model_not_found', $finding->rule);
        self::assertSame('error', $finding->severity);
        self::assertSame('database/factories/CustomerFactory.php', $finding->file);
        self::assertSame(10, $finding->line);
        self::assertStringContainsString('App\\Billing\\Customer', $finding->message);
    }
}
