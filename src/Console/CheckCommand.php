<?php

declare(strict_types=1);

namespace ModelIntegrity\Console;

use Illuminate\Console\Command;
use ModelIntegrity\Analysis\FactoryModelAnalyzer;

final class CheckCommand extends Command
{
    protected $signature = 'model-integrity:check';

    protected $description = 'Check factory model references and conventional mappings';

    public function handle(FactoryModelAnalyzer $analyzer): int
    {
        $report = $analyzer->analyze(base_path());

        foreach ($report->findings() as $finding) {
            $this->line(sprintf(
                '%s %s %s:%d %s Suggestion: %s',
                strtoupper($finding->severity),
                $finding->rule,
                $finding->file,
                $finding->line,
                $finding->message,
                $finding->suggestion,
            ));
        }

        $this->line(sprintf('Result: %d finding(s)', count($report->findings())));

        return $report->hasErrors() ? self::FAILURE : self::SUCCESS;
    }
}
