<?php

declare(strict_types=1);

namespace ModelIntegrity\Reporting;

final readonly class Finding
{
    public function __construct(
        public string $rule,
        public string $severity,
        public string $message,
        public string $file,
        public int $line,
        public string $suggestion,
    ) {}
}
