# Laravel Model Integrity Toolkit

Laravel Model Integrity Toolkit helps developers find likely mistakes in Eloquent model and factory mappings. It analyzes PHP source and reports findings with a rule ID, severity, file location, and suggested action.

## What it checks

| Rule | Severity | Description |
| --- | --- | --- |
| `factory.model_not_found` | Error | A factory explicitly references an `App\...` model that was not found. |
| `factory.model_mapping_suspicious` | Warning | A conventionally named factory explicitly targets a different discovered model. |
| `analysis.parse_error` | Warning | A PHP file could not be parsed. |

A model without a factory is not reported as an error; that may be intentional.

## Requirements

The package declares PHP 8.2 or newer and Laravel 12.x dependencies. Local tests have run with PHP 8.5, but installation in a separate Laravel application and a broader compatibility matrix have not yet been verified.

## Install from a local checkout

In your Laravel application, add the package directory as a Composer path repository. Replace the example path with the location of your checkout:

```bash
composer config repositories.model-integrity path "C:/path/to/seeder-package"
composer require --dev model-integrity/laravel-model-integrity:*
```

Laravel package discovery registers the Artisan command. Run it from your application's root:

```bash
php artisan model-integrity:check
```

## Understand the results

Each finding identifies what to inspect and where to find it. For example, if a factory's `$model` property points to a missing class, check its import, namespace, and class name.

The command exits with:

- `0` when there are no error findings, including when it reports warnings only
- `1` when it reports an error finding

## Safety and limitations

The analyzer parses source files without including them, running migrations or seeders, or connecting to a database. **Artisan still boots the host Laravel application and its service providers**, which may have their own startup behavior.

The current checks cover Eloquent models that use `HasFactory` directly or inherit it through application base classes, plus direct factory subclasses under `database/factories/`. Inheritance chains that leave `app/` before reaching `Model`, `HasFactory` supplied by a custom trait, inherited factory classes, dynamic targets, custom model roots, seeders, and relationships are not yet analyzed. JSON output and configurable severity thresholds are also planned.

An empty report means no issue was found by the current rules; it does not guarantee that every mapping will work at runtime.

## Develop the package

```bash
composer install
composer test
composer format:test
composer analyse
```

See `README.md` for examples and `PROJECT_SCOPE.md` for the roadmap.
