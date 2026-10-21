# Laravel Model Integrity Toolkit

A small Laravel developer package for checking factory model references. It scans PHP source with an AST parser and reports missing explicit model targets and suspicious conventional mappings.

## Why use it, and who is it for?

Laravel can discover a model's factory by naming convention, while a factory can also declare its model with `$model`. A wrong import or an unexpected explicit target may stay unnoticed until code calls the factory. This command points to the source line and suggests what to check, so developers can catch those mistakes while working locally or reviewing a change.

It is most useful for Laravel developers and teams that maintain many models and factories, including projects with plural model names or similar class names in different namespaces. The current rules help with factory mappings; they do not validate seeders, relationships, or every runtime factory path.

## Current scope

- Resolves namespace imports and fully qualified class names.
- Scans `app/` for class declarations and `database/factories/` for direct `Factory` subclasses.
- Reports `factory.model_not_found` as an error with a source location and suggestion.
- Resolves Laravel's default `Database\Factories\...Factory` candidate for direct `App\...` model subclasses using `HasFactory`, preserving plural and irregular class names.
- Reports `factory.model_mapping_suspicious` as a warning when an existing conventional factory explicitly targets a different discovered model. `UseFactory`, a model `$factory` property, or a `newFactory()` method suppresses this convention check.
- Reports PHP parser failures as `analysis.parse_error` warnings.
- Exits with code `1` when an error is found; otherwise exits with code `0`.

The analyzer itself reads PHP source without including it, running migrations or seeders, or connecting to a database. **Running it through Artisan still boots the host Laravel application and its service providers.** Use a disposable environment if your application boot has side effects.

## Compatibility

The declared dependency target is PHP 8.2 or newer and Laravel 12.x. The package has so far been exercised only with PHP 8.5 and the dependency versions in `composer.lock`; broader compatibility requires a CI matrix.

## Local installation

The provisional Composer name is `model-integrity/laravel-model-integrity`. Its PHP namespace is `ModelIntegrity`. In a Laravel 12 application, add a Composer path repository pointing to this package directory, then run:

```bash
composer config repositories.model-integrity path "C:/path/to/seeder-package"
composer require --dev model-integrity/laravel-model-integrity:*
php artisan model-integrity:check
```

Replace the example path with the absolute location of this checkout. Laravel package auto-discovery registers the command. Run it from the consuming application's root; it scans that application's `app/` and `database/factories/` directories. A run with no findings prints `Result: 0 finding(s)` and exits with code `0`. An error finding exits with code `1`; warnings alone still exit with code `0`.

Read each finding's rule ID, file and line, message, and suggestion. For `factory.model_not_found`, inspect the factory's `$model` import or class reference and confirm the intended model exists under `app/`. For `factory.model_mapping_suspicious`, check whether the conventionally named factory should target the named model or whether the model needs an explicit factory override. The latter is a warning because the difference may be intentional.

## Example finding

```text
ERROR factory.model_not_found database/factories/UserFactory.php:10 Factory Database\Factories\UserFactory targets missing model App\Models\Missing. Suggestion: Correct the import or $model reference, or add the model class under app/.
Result: 1 finding(s)
```

When `App\Models\Student` uses `HasFactory`, but the existing `Database\Factories\StudentFactory` declares `$model = App\Models\Teacher::class`, the command reports `factory.model_mapping_suspicious` at the factory's `$model` line. This warning alone exits with code `0`.

## Known limits and planned work

Only explicit `App\...` model references are checked for existence. Convention checking currently covers direct Eloquent model subclasses with a direct `HasFactory` use and an existing default factory class; it does not report absent factories. Inherited models or factories, custom model roots outside `app/`, custom factory name resolvers, dynamic targets, and explicit override targets are not analyzed. Seeders, relationships, JSON output, configuration, and severity thresholds are also planned. An empty report therefore does not prove that every mapping works.

## Suggested next implementation step

Verify path-repository installation and command registration in a separate Laravel 12 application. Then extend factory discovery to inherited models and inherited `HasFactory` use in one tested slice, while preserving the current safeguards for explicit overrides, dynamic references, and intentionally absent factories. Add fixtures for both valid and mismatched mappings before introducing more rules. Seeder and relationship checks should follow only after factory analysis is reliable.

## Development

```bash
composer install
composer test
composer format:test
composer analyse
```

The tests use source fixtures and do not require a database. The package is currently an initial implementation, not a published release.
