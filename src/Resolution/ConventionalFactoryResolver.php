<?php

declare(strict_types=1);

namespace ModelIntegrity\Resolution;

final class ConventionalFactoryResolver
{
    /**
     * Return Laravel's default factory class for an App model.
     *
     * Models outside the default App namespace need application-specific
     * namespace configuration, so this resolver leaves them unresolved.
     */
    public function candidateForModel(string $modelClass): ?string
    {
        if (! str_starts_with($modelClass, 'App\\')) {
            return null;
        }

        $relativeName = substr($modelClass, 4);

        if (str_starts_with($relativeName, 'Models\\')) {
            $relativeName = substr($relativeName, 7);
        }

        return 'Database\\Factories\\'.$relativeName.'Factory';
    }
}
