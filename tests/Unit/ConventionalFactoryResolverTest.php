<?php

declare(strict_types=1);

namespace ModelIntegrity\Tests\Unit;

use ModelIntegrity\Resolution\ConventionalFactoryResolver;
use PHPUnit\Framework\TestCase;

final class ConventionalFactoryResolverTest extends TestCase
{
    public function test_laravel_default_candidate_preserves_plural_and_irregular_names(): void
    {
        $resolver = new ConventionalFactoryResolver;

        self::assertSame('Database\\Factories\\UserFactory', $resolver->candidateForModel('App\\Models\\User'));
        self::assertSame('Database\\Factories\\ClassesFactory', $resolver->candidateForModel('App\\Models\\Classes'));
        self::assertSame('Database\\Factories\\PeopleFactory', $resolver->candidateForModel('App\\Models\\People'));
        self::assertSame('Database\\Factories\\Admin\\StudentFactory', $resolver->candidateForModel('App\\Models\\Admin\\Student'));
        self::assertSame('Database\\Factories\\Admin\\StudentFactory', $resolver->candidateForModel('App\\Admin\\Student'));
        self::assertNull($resolver->candidateForModel('Domain\\Models\\User'));
    }
}
