<?php

declare(strict_types=1);

namespace RavindraDot\ModelIntegrity\Analysis;

use FilesystemIterator;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use RavindraDot\ModelIntegrity\Reporting\Finding;
use RavindraDot\ModelIntegrity\Reporting\Report;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class FactoryModelAnalyzer
{
    public function analyze(string $projectRoot): Report
    {
        $root = rtrim(str_replace('\\', '/', $projectRoot), '/');
        $report = new Report;
        $applicationClasses = [];
        $factories = [];
        $applicationIndexComplete = true;

        foreach ($this->phpFiles($root.'/app') as $file) {
            $findingCount = count($report->findings());
            foreach ($this->classesIn($file, $root, $report) as $class) {
                $applicationClasses[strtolower($class->namespacedName->toString())] = true;
            }
            if (count($report->findings()) !== $findingCount) {
                $applicationIndexComplete = false;
            }
        }

        foreach ($this->phpFiles($root.'/database/factories') as $file) {
            foreach ($this->classesIn($file, $root, $report) as $class) {
                if ($this->extends($class, 'Illuminate\\Database\\Eloquent\\Factories\\Factory')) {
                    $factories[] = [$file, $class];
                }
            }
        }

        foreach ($factories as [$file, $factory]) {
            foreach ($factory->getProperties() as $property) {
                foreach ($property->props as $propertyValue) {
                    if ($propertyValue->name->toString() !== 'model'
                        || ! $propertyValue->default instanceof Node\Expr\ClassConstFetch
                        || ! $propertyValue->default->class instanceof Node\Name
                        || $propertyValue->default->name->toLowerString() !== 'class') {
                        continue;
                    }

                    $target = $propertyValue->default->class->toString();

                    // The first rule covers application classes. Vendor and dynamic
                    // model references need broader resolution before they can be errors.
                    if (! $applicationIndexComplete
                        || ! str_starts_with(strtolower($target), 'app\\')
                        || isset($applicationClasses[strtolower($target)])) {
                        continue;
                    }

                    $report->add(new Finding(
                        'factory.model_not_found',
                        'error',
                        sprintf('Factory %s targets missing model %s.', $factory->namespacedName->toString(), $target),
                        $this->relativePath($file, $root),
                        $propertyValue->default->getStartLine(),
                        'Correct the import or $model reference, or add the model class under app/.',
                    ));
                }
            }
        }

        return $report;
    }

    /** @return list<string> */
    private function phpFiles(string $directory): array
    {
        if (! is_dir($directory)) {
            return [];
        }

        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));

        foreach ($iterator as $item) {
            if ($item->isFile() && strtolower($item->getExtension()) === 'php') {
                $files[] = str_replace('\\', '/', $item->getPathname());
            }
        }

        sort($files);

        return $files;
    }

    /** @return list<Node\Stmt\Class_> */
    private function classesIn(string $file, string $root, Report $report): array
    {
        $source = file_get_contents($file);
        if ($source === false) {
            return [];
        }

        try {
            $statements = (new ParserFactory)->createForNewestSupportedVersion()->parse($source);
        } catch (Error $error) {
            $report->add(new Finding(
                'analysis.parse_error',
                'warning',
                'Could not parse PHP source: '.$error->getRawMessage(),
                $this->relativePath($file, $root),
                $error->getStartLine(),
                'Fix the syntax and run the check again.',
            ));

            return [];
        }

        $traverser = new NodeTraverser(new NameResolver);
        $statements = $traverser->traverse($statements ?? []);

        /** @var list<Node\Stmt\Class_> $classes */
        $classes = (new NodeFinder)->findInstanceOf($statements, Node\Stmt\Class_::class);

        return array_values(array_filter($classes, static fn (Node\Stmt\Class_ $class): bool => $class->name !== null && isset($class->namespacedName)));
    }

    private function extends(Node\Stmt\Class_ $class, string $parent): bool
    {
        return $class->extends instanceof Node\Name
            && strcasecmp($class->extends->toString(), $parent) === 0;
    }

    private function relativePath(string $file, string $root): string
    {
        return ltrim(substr($file, strlen($root)), '/');
    }
}
