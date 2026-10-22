<?php

declare(strict_types=1);

namespace ModelIntegrity\Tests\Unit;

use ModelIntegrity\Analysis\FactoryModelAnalyzer;
use PHPUnit\Framework\TestCase;

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

    public function test_conventional_factory_target_mismatch_is_a_warning_without_flagging_absent_or_overridden_factories(): void
    {
        $report = (new FactoryModelAnalyzer)->analyze(__DIR__.'/../Fixtures/conventional-mapping');

        self::assertCount(1, $report->findings());
        $finding = $report->findings()[0];
        self::assertSame('factory.model_mapping_suspicious', $finding->rule);
        self::assertSame('warning', $finding->severity);
        self::assertSame('database/factories/StudentFactory.php', $finding->file);
        self::assertSame(10, $finding->line);
        self::assertStringContainsString('App\\Models\\Student', $finding->message);
        self::assertStringContainsString('App\\Models\\Teacher', $finding->message);
        self::assertFalse($report->hasErrors());
    }

    public function test_inherited_has_factory_is_analyzed_and_inherited_overrides_are_respected(): void
    {
        $report = (new FactoryModelAnalyzer)->analyze(__DIR__.'/../Fixtures/inherited-mapping');
        $findings = $report->findings();

        self::assertCount(2, $findings);

        $files = array_map(static fn ($finding): string => $finding->file, $findings);
        sort($files);

        self::assertSame([
            'database/factories/AttributeChildFactory.php',
            'database/factories/AuthorFactory.php',
        ], $files);

        foreach ($findings as $finding) {
            self::assertSame('factory.model_mapping_suspicious', $finding->rule);
            self::assertSame('warning', $finding->severity);
            self::assertSame(10, $finding->line);
        }

        $messages = implode("\n", array_map(static fn ($finding): string => $finding->message, $findings));
        self::assertStringContainsString('App\\Models\\Author', $messages);
        self::assertStringContainsString('App\\Models\\AttributeChild', $messages);
        self::assertFalse($report->hasErrors());
    }
}
