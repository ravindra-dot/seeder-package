<?php

declare(strict_types=1);

namespace ModelIntegrity\Reporting;

final class Report
{
    /** @var list<Finding> */
    private array $findings = [];

    public function add(Finding $finding): void
    {
        $this->findings[] = $finding;
    }

    /** @return list<Finding> */
    public function findings(): array
    {
        return $this->findings;
    }

    public function hasErrors(): bool
    {
        foreach ($this->findings as $finding) {
            if ($finding->severity === 'error') {
                return true;
            }
        }

        return false;
    }
}
