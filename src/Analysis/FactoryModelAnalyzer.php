<?php

declare(strict_types=1);

namespace ModelIntegrity\Analysis;

use FilesystemIterator;
use ModelIntegrity\Reporting\Finding;
use ModelIntegrity\Reporting\Report;
use ModelIntegrity\Resolution\ConventionalFactoryResolver;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeFinder;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\ParserFactory;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class FactoryModelAnalyzer
{
    public function analyze(string $projectRoot): Report
    {
        $root = rtrim(str_replace('\\', '/', $projectRoot), '/');
        $report = new Report;
        /** @var array<string, array{class: Node\Stmt\Class_, file: string}> $classes */
        $classes = [];
        $factories = [];
        $applicationIndexComplete = true;

        foreach ($this->phpFiles($root.'/app') as $file) {
            $findingCount = count($report->findings());
            foreach ($this->classesIn($file, $root, $report) as $class) {
                $classes[strtolower($class->namespacedName->toString())] = ['class' => $class, 'file' => $file];
            }
            if (count($report->findings()) !== $findingCount) {
                $applicationIndexComplete = false;
            }
        }

        foreach ($this->phpFiles($root.'/database/factories') as $file) {
            foreach ($this->classesIn($file, $root, $report) as $class) {
                if ($this->extends($class, 'Illuminate\\Database\\Eloquent\\Factories\\Factory')) {
                    $factories[strtolower($class->namespacedName->toString())] = [$file, $class];
                }
            }
        }

        foreach ($factories as [$file, $factory]) {
            $modelReference = $this->explicitModelReference($factory);
            if ($modelReference === null) {
                continue;
            }

            $target = $modelReference->class->toString();

            // Vendor and dynamic model references need broader resolution
            // before they can be reported as missing application classes.
            if (! $applicationIndexComplete
                || ! str_starts_with(strtolower($target), 'app\\')
                || isset($classes[strtolower($target)])) {
                continue;
            }

            $report->add(new Finding(
                'factory.model_not_found',
                'error',
                sprintf('Factory %s targets missing model %s.', $factory->namespacedName->toString(), $target),
                $this->relativePath($file, $root),
                $modelReference->getStartLine(),
                'Correct the import or $model reference, or add the model class under app/.',
            ));
        }

        $resolver = new ConventionalFactoryResolver;

        foreach ($classes as $modelKey => $modelEntry) {
            $model = $modelEntry['class'];

            if (! $this->isModel($modelKey, $classes)
                || ! $this->inheritsHasFactory($modelKey, $classes)
                || $this->hasFactoryOverride($modelKey, $classes)) {
                continue;
            }

            $modelName = $model->namespacedName->toString();
            $candidate = $resolver->candidateForModel($modelName);
            if ($candidate === null || ! isset($factories[strtolower($candidate)])) {
                continue;
            }

            [$file, $factory] = $factories[strtolower($candidate)];
            $modelReference = $this->explicitModelReference($factory);
            if ($modelReference === null) {
                continue;
            }

            $target = $modelReference->class->toString();
            if (! isset($classes[strtolower($target)])
                || strcasecmp($target, $modelName) === 0) {
                continue;
            }

            $report->add(new Finding(
                'factory.model_mapping_suspicious',
                'warning',
                sprintf('Model %s conventionally resolves to %s, but that factory targets %s.', $modelName, $candidate, $target),
                $this->relativePath($file, $root),
                $modelReference->getStartLine(),
                'Correct the factory $model reference or explicitly configure the model factory.',
            ));
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

    private function usesTrait(Node\Stmt\Class_ $class, string $trait): bool
    {
        foreach ($class->stmts as $statement) {
            if (! $statement instanceof Node\Stmt\TraitUse) {
                continue;
            }

            foreach ($statement->traits as $usedTrait) {
                if (strcasecmp($usedTrait->toString(), $trait) === 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether the class is an application model by following its
     * inheritance chain. Chains that leave the application index are left
     * unresolved so unknown vendor parents cannot cause false findings.
     *
     * @param  array<string, array{class: Node\Stmt\Class_, file: string}>  $classes
     */
    private function isModel(string $key, array $classes): bool
    {
        $seen = [];

        while (! isset($seen[$key])) {
            $seen[$key] = true;

            $class = $classes[$key]['class'] ?? null;
            if ($class === null) {
                return false;
            }

            $parent = $this->parentName($class);
            if ($parent === null) {
                return false;
            }

            if (strcasecmp($parent, 'Illuminate\\Database\\Eloquent\\Model') === 0) {
                return true;
            }

            $key = strtolower($parent);
        }

        return false;
    }

    /**
     * HasFactory is inherited from an application base class, so the trait
     * does not need to be used directly by the model.
     *
     * @param  array<string, array{class: Node\Stmt\Class_, file: string}>  $classes
     */
    private function inheritsHasFactory(string $key, array $classes): bool
    {
        $seen = [];

        while (! isset($seen[$key])) {
            $seen[$key] = true;

            $class = $classes[$key]['class'] ?? null;
            if ($class === null) {
                return false;
            }

            if ($this->usesTrait($class, 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory')) {
                return true;
            }

            $parent = $this->parentName($class);
            if ($parent === null) {
                return false;
            }

            $key = strtolower($parent);
        }

        return false;
    }

    /**
     * A UseFactory attribute applies only to the class that declares it, while
     * a $factory property or newFactory() method is inherited by descendants.
     *
     * @param  array<string, array{class: Node\Stmt\Class_, file: string}>  $classes
     */
    private function hasFactoryOverride(string $key, array $classes): bool
    {
        $seen = [];
        $isDeclaringClass = true;

        while (! isset($seen[$key])) {
            $seen[$key] = true;

            $class = $classes[$key]['class'] ?? null;
            if ($class === null) {
                return false;
            }

            if ($isDeclaringClass && $this->hasUseFactoryAttribute($class)) {
                return true;
            }

            if ($this->declaresFactoryOverride($class)) {
                return true;
            }

            $isDeclaringClass = false;

            $parent = $this->parentName($class);
            if ($parent === null) {
                return false;
            }

            $key = strtolower($parent);
        }

        return false;
    }

    private function hasUseFactoryAttribute(Node\Stmt\Class_ $class): bool
    {
        foreach ($class->attrGroups as $group) {
            foreach ($group->attrs as $attribute) {
                if (strcasecmp($attribute->name->toString(), 'Illuminate\\Database\\Eloquent\\Attributes\\UseFactory') === 0) {
                    return true;
                }
            }
        }

        return false;
    }

    private function declaresFactoryOverride(Node\Stmt\Class_ $class): bool
    {
        foreach ($class->getMethods() as $method) {
            if (strcasecmp($method->name->toString(), 'newFactory') === 0) {
                return true;
            }
        }

        foreach ($class->getProperties() as $property) {
            foreach ($property->props as $propertyValue) {
                if ($propertyValue->name->toString() === 'factory') {
                    return true;
                }
            }
        }

        return false;
    }

    private function parentName(Node\Stmt\Class_ $class): ?string
    {
        return $class->extends instanceof Node\Name ? $class->extends->toString() : null;
    }

    private function explicitModelReference(Node\Stmt\Class_ $factory): ?Node\Expr\ClassConstFetch
    {
        foreach ($factory->getProperties() as $property) {
            foreach ($property->props as $propertyValue) {
                if ($propertyValue->name->toString() === 'model'
                    && $propertyValue->default instanceof Node\Expr\ClassConstFetch
                    && $propertyValue->default->class instanceof Node\Name
                    && $propertyValue->default->name->toLowerString() === 'class') {
                    return $propertyValue->default;
                }
            }
        }

        return null;
    }

    private function relativePath(string $file, string $root): string
    {
        return ltrim(substr($file, strlen($root)), '/');
    }
}
