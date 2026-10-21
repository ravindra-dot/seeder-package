# Laravel Model Integrity Toolkit

A small Laravel developer package for checking explicit factory model references. This first release scans PHP source with an AST parser and reports when a factory's static `$model = SomeClass::class` target names an application model that was not discovered under `app/`.

## Current scope

- Resolves namespace imports and fully qualified class names.
- Scans `app/` for class declarations and `database/factories/` for direct `Factory` subclasses.
- Reports `factory.model_not_found` as an error with a source location and suggestion.
- Reports PHP parser failures as `analysis.parse_error` warnings.
- Exits with code `1` when an error is found; otherwise exits with code `0`.

The analyzer itself reads PHP source without including it, running migrations or seeders, or connecting to a database. **Running it through Artisan still boots the host Laravel application and its service providers.** Use a disposable environment if your application boot has side effects.

## Compatibility

The declared dependency target is PHP 8.2 or newer and Laravel 12.x. The package has so far been exercised only with PHP 8.5 and the dependency versions in `composer.lock`; broader compatibility requires a CI matrix.

## Local installation

The provisional Composer name is `ravindra-dot/laravel-model-integrity`. Its PHP namespace is `RavindraDot\ModelIntegrity`. In a Laravel 12 application, add a Composer path repository pointing to this package directory, then run:

```bash
composer require --dev ravindra-dot/laravel-model-integrity:*
php artisan model-integrity:check
```

Laravel package auto-discovery registers the command. A successful run with no findings prints `Result: 0 finding(s)`.

## Example finding

```text
ERROR factory.model_not_found database/factories/UserFactory.php:10 Factory Database\Factories\UserFactory targets missing model App\Models\Missing. Suggestion: Correct the import or $model reference, or add the model class under app/.
Result: 1 finding(s)
```

## Known limits and planned work

Only explicit `App\...` model references are checked for existence. Dynamic targets, inherited factory classes, custom model roots outside `app/`, factory naming conventions, seeders, relationships, JSON output, configuration, and severity thresholds are not analyzed yet. An empty report therefore does not prove that every mapping works. Missing factories are not reported, because many models intentionally have none.

## Development

```bash
composer install
composer test
composer format:test
composer analyse
```

The tests use source fixtures and do not require a database. The package is currently an initial implementation, not a published release.
