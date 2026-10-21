# Laravel Model Integrity Toolkit

## Project Scope, Architecture, Implementation Plan, and Usage

**Working name:** Laravel Model Integrity Toolkit\
**Package type:** Composer-installable Laravel developer package\
**Primary goal:** Detect and explain mismatches between Eloquent Models,
Factories, Seeders, relationships, namespaces, and related conventions
before they become runtime errors.

> The name is provisional. The first release should prioritize reliable
> diagnostics over automatic code modification.

### Implementation status (October 2026)

This document is the **roadmap for the full toolkit**, not a list of
features already available. The current code implements a smaller first
slice; `README.md` is the source of truth for commands that work today.

| Area | Status |
| --- | --- |
| Composer package, Laravel service provider, and `model-integrity:check` command | Implemented locally |
| AST scanning of classes under `app/` and direct factory subclasses under `database/factories/` | Implemented |
| Explicit `App\...` factory `$model` reference check (`factory.model_not_found`) | Implemented and tested |
| Parse-error warning (`analysis.parse_error`) and basic console exit codes | Implemented and tested |
| Convention-based factory mapping, other factory rules, seeders, relationships, JSON, and configuration | Planned |
| Installation in a separate Laravel app and PHP/Laravel compatibility matrix | Not yet verified |

The current command uses Artisan, which boots the host Laravel
application and its service providers. The analyzer itself parses source
without including application files or running migrations or seeders.

------------------------------------------------------------------------

## 1. Project Overview

Laravel relies on class names, namespaces, conventions, and explicit
relationships to connect models, factories, and seeders. These
connections can break when a project uses plural or irregular model
names, custom namespaces, incorrect imports, misspelled relationship
methods, or factories that target the wrong model.

The planned toolkit scans a Laravel application and produces actionable
findings with the file, line where available, severity, explanation,
and suggested fix.

### Example problems

-   A model named `Classes` does not map automatically to a factory
    named `ClassFactory` in the way a developer expects.
-   A factory imports the wrong model or points to a class that does not
    exist.
-   A seeder references a missing factory or misspells a class name.
-   A relationship method calls `belongTo()` instead of `belongsTo()`.
-   A relationship targets the wrong model class.
-   A model is expected to expose `students()` but the method is
    missing.
-   A factory uses invalid Faker syntax or calls an undefined method.
-   A seeder depends on records that have not been created yet.
-   A custom model namespace is not handled by convention-based
    discovery.

### Product principle

**Static analysis first; execution only when explicitly requested.**

The default check must not migrate, seed, delete, or mutate the user's
database. It should inspect source files and report likely issues
safely.

------------------------------------------------------------------------

## 2. Goals and Non-Goals

### Goals for the planned full version 1

1.  Discover Eloquent models, factories, and seeders.
2.  Resolve class names and namespaces as accurately as practical.
3.  Detect missing or suspicious Model--Factory--Seeder mappings.
4.  Validate factory model references.
5.  Inspect common relationship definitions and references.
6.  Detect common misspelled Eloquent relationship methods.
7.  Identify likely factory and seeder reference errors.
8.  Report findings in readable terminal output.
9.  Support machine-readable JSON output for future CI integration.
10. Provide tests using small fixture Laravel applications or
    representative source fixtures.
11. Support multiple Laravel/PHP versions through an explicit
    compatibility matrix.
12. Avoid false claims: findings that cannot be proven statically must
    be marked as warnings or informational.

### Non-goals for version 1

-   Replacing PHPStan, Larastan, Psalm, or an IDE's PHP language server.
-   Proving every runtime behavior of arbitrary PHP code.
-   Automatically rewriting files by default.
-   Running migrations or seeders automatically.
-   Managing production data.
-   Generating a full admin dashboard.
-   Supporting every third-party ORM or framework.
-   Building a VS Code extension before the CLI and analysis engine are
    stable.

------------------------------------------------------------------------

## 3. Target Users and Main Use Cases

### Target users

-   Laravel developers working on medium or large applications.
-   Teams with many models, factories, and seeders.
-   Developers learning Eloquent conventions.
-   Maintainers who want a repeatable pre-commit or CI check.

### Planned core use cases

**Use case A --- Find naming mismatches**

A developer adds `app/Models/Classes.php` and expects a factory to be
discovered. The toolkit identifies the model and candidate factory
names, explains Laravel's convention, and suggests either a conventional
class name or an explicit mapping.

**Use case B --- Validate factory model mapping**

A factory's `$model` property or inferred model points to a missing or
unexpected class. The report shows the factory file and the unresolved
target.

**Use case C --- Check seeder references**

A seeder calls a missing factory, references an invalid class, or uses a
suspicious model/factory pair.

**Use case D --- Inspect relationships**

A relationship method contains a known typo, references a class that
cannot be resolved, or appears to point to an unexpected model.

**Use case E --- Run checks in CI**

A project runs the command during CI and fails the build only for
configured error-level findings.

------------------------------------------------------------------------

## 4. Product Design and Package Architecture

Use a Composer package with a small Laravel integration layer and a
framework-independent analysis core where practical.

Suggested package layout:

``` text
laravel-model-integrity/
├── composer.json
├── README.md
├── LICENSE
├── CHANGELOG.md
├── CONTRIBUTING.md
├── phpunit.xml
├── phpstan.neon.dist
├── pint.json
├── config/
│   └── model-integrity.php
├── src/
│   ├── ModelIntegrityServiceProvider.php
│   ├── Console/
│   │   └── CheckCommand.php
│   ├── Configuration/
│   │   └── AnalysisConfig.php
│   ├── Discovery/
│   │   ├── ProjectScanner.php
│   │   ├── ModelDiscovery.php
│   │   ├── FactoryDiscovery.php
│   │   └── SeederDiscovery.php
│   ├── Parsing/
│   │   ├── PhpFileParser.php
│   │   └── ParsedClass.php
│   ├── Resolution/
│   │   ├── ClassNameResolver.php
│   │   ├── ModelFactoryResolver.php
│   │   └── SeederReferenceResolver.php
│   ├── Analysis/
│   │   ├── AnalysisRunner.php
│   │   ├── Rules/
│   │   │   ├── Rule.php
│   │   │   ├── MissingFactoryRule.php
│   │   │   ├── FactoryModelMismatchRule.php
│   │   │   ├── MissingSeederReferenceRule.php
│   │   │   ├── UnknownRelationshipMethodRule.php
│   │   │   ├── UnresolvedRelationshipTargetRule.php
│   │   │   └── SuspiciousRelationshipRule.php
│   │   └── RuleRegistry.php
│   ├── Reporting/
│   │   ├── Finding.php
│   │   ├── Report.php
│   │   ├── ConsoleReporter.php
│   │   └── JsonReporter.php
│   └── Support/
│       ├── PathNormalizer.php
│       └── SourceLocation.php
├── tests/
│   ├── Unit/
│   ├── Feature/
│   └── Fixtures/
│       ├── valid-project/
│       ├── naming-mismatches/
│       ├── relationship-errors/
│       └── seeder-errors/
└── .github/
    └── workflows/
        └── tests.yml
```

This is a target structure, not a requirement to create every file on
day one. Start with the smallest vertical slice and add files only when
their responsibility is needed.

### Architectural boundaries

-   **Discovery** finds files and candidate classes; it should not
    decide whether a mapping is valid.
-   **Parsing** extracts source facts without executing application
    code.
-   **Resolution** determines likely class relationships using
    namespaces, imports, conventions, and explicit declarations.
-   **Analysis rules** turn facts into findings.
-   **Reporting** formats findings and should not contain analysis
    logic.
-   **Laravel integration** registers the command and publishes
    configuration.
-   **Tests** verify rules against valid and invalid source fixtures.

------------------------------------------------------------------------

## 5. Technical Decisions

### Language and dependencies

-   PHP package targeting the minimum PHP version supported by the
    selected Laravel compatibility range.
-   Composer for dependency management and distribution.
-   Laravel package auto-discovery through a service provider.
-   PHPUnit or Pest for tests; choose one test framework and use it
    consistently.
-   Laravel Pint for formatting.
-   PHPStan for the package's own static analysis.
-   A PHP parser such as `nikic/php-parser` is recommended for reliable
    namespace, import, class, and method inspection. Avoid relying on
    regular expressions alone for PHP syntax.
-   Keep runtime dependencies minimal. Development tools belong in
    `require-dev`.

Before publishing, decide and document the exact supported Laravel and
PHP versions. Do not claim broad compatibility until the test matrix
passes.

### Why not execute application files to inspect them?

Executing application source can trigger service providers, environment
access, database connections, or other side effects. The default
analyzer should parse source statically. An optional runtime check may
be considered later, with clear opt-in behavior and safeguards.

------------------------------------------------------------------------

## 6. Analysis Model

The analysis engine should build a lightweight project index.

### Entities to index

-   Model class: fully qualified class name, file path, namespace,
    imports, class name, traits, relevant properties and methods.
-   Factory class: fully qualified class name, file path, declared
    `$model` property if statically readable, factory naming candidate,
    and model references.
-   Seeder class: fully qualified class name, file path, referenced
    model/factory classes and common factory calls.
-   Relationship: declaring model, method name, relationship method
    invoked, target class if explicitly supplied, and source location.
-   Finding: rule identifier, severity, message, file, line, evidence,
    and suggested remediation.

### Finding schema

Each finding should contain:

-   `rule`: stable machine-readable rule ID.
-   `severity`: `error`, `warning`, or `info`.
-   `message`: concise explanation.
-   `file`: project-relative path when known.
-   `line`: line number when known; otherwise null.
-   `symbol`: relevant class or method when known.
-   `suggestion`: practical next step.
-   `confidence`: optional value such as `high`, `medium`, or `low`.

Do not use confidence to imply mathematical certainty. It communicates
how strong the static evidence is.

### Severity policy

-   **Error:** a high-confidence unresolved class or a clearly invalid
    reference.
-   **Warning:** a likely mismatch, suspicious convention, or
    relationship issue requiring developer review.
-   **Info:** a convention difference that may be intentional.

A project must be able to configure severity overrides.

------------------------------------------------------------------------

## 7. Rule Scope

### Phase 1 --- Factory rules (build these first)

  ------------------------------------------------------------------------------------
  Rule ID                              Purpose                 Default severity
  ------------------------------------ ----------------------- -----------------------
  `factory.class_not_found`            A factory class         Error
                                       reference cannot be     
                                       resolved                

  `factory.model_not_found`            A factory's explicit    Error
                                       model target does not   
                                       exist                   

  `factory.model_mapping_suspicious`   Factory naming or model Warning
                                       mapping appears         
                                       inconsistent            

  `factory.model_not_discovered`       A discovered model has  Warning
                                       no matching factory     
                                       candidate               

  `factory.namespace_mismatch`         Namespace/imports       Warning
                                       appear inconsistent     
                                       with the class location 

  `factory.faker_syntax_suspicious`    A known, confidently    Warning
                                       detectable Faker misuse 
                                       is found                
  ------------------------------------------------------------------------------------

Factory discovery should understand the normal `Database\Factories`
namespace and Laravel's model-to-factory convention, while allowing
explicit mappings and custom namespaces. A missing conventional factory
is not necessarily an error: a project may not need a factory for every
model.

### Phase 2 --- Seeder rules

  --------------------------------------------------------------------------------------
  Rule ID                                Purpose                 Default severity
  -------------------------------------- ----------------------- -----------------------
  `seeder.class_not_found`               A referenced seeder or  Error
                                         class cannot be         
                                         resolved                

  `seeder.factory_reference_missing`     A statically            Error
                                         identifiable factory    
                                         reference cannot be     
                                         resolved                

  `seeder.model_factory_mismatch`        A seeder appears to use Warning
                                         the wrong factory for a 
                                         model                   

  `seeder.dependency_order_suspicious`   A detectable dependency Warning
                                         may be used before      
                                         required data exists    

  `seeder.model_reference_missing`       A referenced model      Error
                                         class cannot be         
                                         resolved                
  --------------------------------------------------------------------------------------

Seeder execution order is difficult to prove statically. Report only
evidence-backed risks and explain uncertainty.

### Phase 3 --- Relationship rules

  ---------------------------------------------------------------------------------------
  Rule ID                                 Purpose                 Default severity
  --------------------------------------- ----------------------- -----------------------
  `relationship.unknown_method`           A likely misspelling of Warning
                                          a recognized Eloquent   
                                          relationship method is  
                                          found                   

  `relationship.target_not_found`         An explicit             Error
                                          relationship target     
                                          class cannot be         
                                          resolved                

  `relationship.method_missing`           A statically referenced Warning
                                          relationship method is  
                                          absent from the target  
                                          model                   

  `relationship.suspicious_target`        A relationship target   Warning
                                          appears inconsistent    
                                          with imports or         
                                          conventions             

  `relationship.foreign_key_suspicious`   A statically obvious    Info
                                          foreign-key naming      
                                          mismatch is detected    
  ---------------------------------------------------------------------------------------

Recognized Eloquent relationship methods may include `belongsTo`,
`hasOne`, `hasMany`, `belongsToMany`, `hasManyThrough`, `hasOneThrough`,
`morphTo`, `morphOne`, `morphMany`, `morphToMany`, and `morphedByMany`.
Keep this list version-aware and configurable where useful.

Do not flag every custom method call as an invalid relationship. The
rule must first establish that the method is being used in a
relationship-like context.

### Phase 4 --- Optional extensions

Only after the core rules are reliable:

-   JSON report output for CI.
-   Baseline file to suppress existing findings while preventing new
    ones.
-   Ignore paths and per-rule severity configuration.
-   Suggestions for conventional class names.
-   Optional safe-fix mode for narrowly scoped changes.
-   IDE or editor integration.
-   Additional checks for policies, observers, events, jobs, and
    notifications.

------------------------------------------------------------------------

## 8. Important Laravel Naming Cases

### Plural model names

Example:

``` php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
}
```

Possible factory:

``` php
namespace Database\Factories;

use App\Models\Classes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassesFactory extends Factory
{
    protected $model = Classes::class;

    public function definition(): array
    {
        return [
            // Add project-specific attributes.
        ];
    }
}
```

The analyzer should distinguish between:

1.  A factory that Laravel can discover by convention.
2.  A factory that works because the model explicitly configures the
    mapping.
3.  A factory that exists but points to a different model.
4.  A model that intentionally has no factory.

Do not assume that every plural model is invalid. The package should
explain convention differences and recommend explicit configuration or
conventional names when appropriate.

### Wrong imports

``` php
use App\Models\Student;

// The file actually defines App\Models\Students.
```

The parser should resolve imports to fully qualified class names and
compare them with discovered declarations. Avoid comparing only short
class names, because different namespaces can contain classes with the
same short name.

### Misspelled relationship method

``` php
public function school()
{
    return $this->belongTo(School::class);
}
```

The analyzer may report `belongTo` as a likely typo for `belongsTo`. It
should not attempt to execute the model to verify this.

### Missing target relationship

A model calls `$student->classes()` while the `Student` class does not
define `classes()`. This can be checked only when the call and target
type are statically resolvable. Dynamic calls, traits, inherited
methods, and magic methods may complicate the conclusion; account for
those cases before reporting an error.

------------------------------------------------------------------------

## 9. CLI Design

Proposed command:

``` bash
php artisan model-integrity:check
```

Useful options:

``` bash
php artisan model-integrity:check --format=console
php artisan model-integrity:check --format=json
php artisan model-integrity:check --only=factories
php artisan model-integrity:check --only=seeders
php artisan model-integrity:check --only=relationships
php artisan model-integrity:check --path=app/Models
php artisan model-integrity:check --no-progress
```

These options are proposed; implement only those that are supported and
tested.

### Exit codes

-   `0`: analysis completed with no findings at or above the configured
    failure threshold.
-   `1`: analysis completed and found issues at or above the failure
    threshold.
-   `2`: the command could not run because of invalid configuration or
    an internal error.

Use stable exit behavior so CI systems can rely on it.

### Example console output

``` text
Laravel Model Integrity Toolkit

Scanned:
  Models:    24
  Factories: 19
  Seeders:   8

ERROR   factory.model_not_found
        database/factories/ClassFactory.php:12
        Model target App\Models\Class could not be resolved.
        Check the namespace/import or configure the correct model.

WARNING factory.model_mapping_suspicious
        app/Models/Classes.php:8
        No conventional factory candidate was resolved confidently.
        Verify the factory name or define an explicit mapping.

WARNING relationship.unknown_method
        app/Models/Student.php:24
        "belongTo" may be a typo for "belongsTo".

Result: 3 findings (1 error, 2 warnings)
```

The numbers above are illustrative only.

------------------------------------------------------------------------

## 10. Configuration Design

Publish a configuration file only when the service provider and command
work.

Example target configuration:

``` php
<?php

return [
    'paths' => [
        'models' => ['app/Models'],
        'factories' => ['database/factories'],
        'seeders' => ['database/seeders'],
    ],

    'exclude' => [
        'vendor',
        'storage',
        'bootstrap/cache',
    ],

    'rules' => [
        'factory.class_not_found' => 'error',
        'factory.model_not_found' => 'error',
        'factory.model_mapping_suspicious' => 'warning',
        'factory.model_not_discovered' => 'warning',
        'seeder.factory_reference_missing' => 'error',
        'relationship.unknown_method' => 'warning',
        'relationship.target_not_found' => 'error',
    ],

    'fail_on' => 'error',

    'report' => [
        'format' => 'console',
    ],
];
```

Treat this as an initial design. Validate configuration values, document
defaults, and make the actual config match implemented rules. Do not
silently accept unknown rule names or invalid severity values.

------------------------------------------------------------------------

## 11. Implementation Roadmap

Build one complete, tested slice at a time. Do not create every
placeholder file before the first test passes.

### Phase 0 --- Package foundation

**Status:** The core local foundation is implemented. A CI workflow,
installation in a separate consumer application, and a compatibility
matrix remain to be completed.

**Deliverables** - Composer package metadata and namespace. - Supported
PHP/Laravel version policy. - Service provider registration and command
registration. - PHPUnit/Pest setup, Pint, and PHPStan configuration. -
Minimal package README. - CI workflow running tests and code-quality
checks.

**Acceptance criteria** - Package installs in a compatible Laravel
application. - `php artisan list` shows the command. - The command can
run against a fixture project without modifying its database.

### Phase 1 --- Factory discovery and mapping

**Status:** In progress. Class discovery and the explicit missing-model
rule are implemented; convention-based resolution and the remaining
factory rules are planned.

**Implementation order** 1. Define a `Finding` value object and a simple
`Report`. 2. Implement path scanning with exclusions. 3. Parse PHP files
using an AST parser. 4. Discover model and factory class declarations.
5. Record namespaces and imports. 6. Detect explicit factory model
targets. 7. Implement convention-based candidate resolution. 8. Add the
first factory rules. 9. Produce console output. 10. Add tests for valid
mappings, missing factories, plural models, custom namespaces, wrong
imports, and duplicate short class names.

**Acceptance criteria** - Correctly resolves fully qualified class
names. - Does not treat all plural model names as errors. -
Distinguishes explicit mappings from inferred mappings. - Reports file
locations when possible. - Produces no database connections or writes.

### Phase 2 --- Seeder discovery and analysis

**Implementation order** 1. Discover seeder classes. 2. Parse common
calls such as `Model::factory()`, `Factory::new()`, and `call([...])`
where statically identifiable. 3. Resolve referenced model and factory
classes. 4. Compare references with the project index. 5. Add
missing-reference and suspicious-mapping rules. 6. Test nested seeder
calls, custom namespaces, and dynamic references.

**Acceptance criteria** - Detects high-confidence missing class
references. - Avoids declaring dynamic or conditional logic invalid
without enough evidence. - Clearly labels uncertain dependency-order
findings as warnings.

### Phase 3 --- Relationship analysis

**Implementation order** 1. Identify model methods and method calls in
return expressions. 2. Detect known Eloquent relationship methods. 3.
Detect likely misspellings against the known method list. 4. Resolve
explicit class-string targets and imported class names. 5. Account for
traits and inheritance where practical. 6. Check statically resolvable
relationship-method calls. 7. Add tests for correct relations,
misspellings, invalid targets, custom methods, and inherited methods.

**Acceptance criteria** - Correct relationship methods do not produce
findings. - Custom methods are not automatically treated as invalid
relationships. - Findings explain static-analysis limitations.

### Phase 4 --- JSON output and CI support

**Deliverables** - Stable JSON schema. - Configurable severity
thresholds. - Predictable exit codes. - Documentation for GitHub Actions
or other CI systems. - Tests for JSON validity and exit behavior.

**Acceptance criteria** - JSON output can be consumed by scripts. - CI
fails only according to the configured threshold. - The report format is
documented and tested.

### Phase 5 --- Baseline and quality improvements

Potential deliverables: - Baseline of existing findings. - Ignore
patterns. - Better support for traits, inheritance, custom namespaces,
and monorepos. - Performance improvements for large codebases. -
Optional suggestions or safe fixes after confidence is established.

Do not start an editor extension or auto-fix engine until the CLI rules
are reliable.

------------------------------------------------------------------------

## 12. Testing Strategy

Use unit tests for each rule and feature tests for the Artisan command.
Include representative fixture files rather than relying only on the
package's own source tree.

### Required fixture categories

1.  **Valid project:** conventional model, factory, seeder, and
    relationships.
2.  **Plural names:** `Classes`, `Sections`, and `Students`.
3.  **Explicit mappings:** factory declares its model explicitly.
4.  **Wrong imports:** same short class name in different namespaces.
5.  **Missing classes:** nonexistent factory/model/seeder target.
6.  **Relationship typos:** `belongTo()` versus `belongsTo()`.
7.  **Custom relationships:** methods that are not Eloquent relationship
    methods.
8.  **Traits and inheritance:** methods supplied outside the immediate
    class.
9.  **Dynamic code:** references that cannot be resolved statically.
10. **Excluded paths:** vendor files and configured exclusions.
11. **Malformed PHP:** parser errors are reported safely rather than
    crashing without context.
12. **Output:** console format, JSON format, and exit codes.

### Quality gates

Before a release:

-   All tests pass.
-   Pint passes.
-   PHPStan passes at the selected level.
-   The package installs in every supported Laravel/PHP combination.
-   No test requires a production database.
-   The README examples are tested or verified.
-   Findings have stable rule IDs and useful remediation messages.

------------------------------------------------------------------------

## 13. Security and Safety Requirements

-   Never run `db:seed`, `migrate`, or arbitrary project PHP code during
    a default static scan.
-   Never delete, update, or truncate application tables.
-   Do not print environment secrets or `.env` contents.
-   Exclude `vendor`, generated cache, and other irrelevant paths by
    default.
-   Normalize paths before comparing them.
-   Handle unreadable files and parse errors without exposing sensitive
    file contents.
-   Any future fix mode must show planned changes and require explicit
    opt-in.
-   Never automatically rename classes or files in the initial release.

------------------------------------------------------------------------

## 14. Installation and Developer Usage

Once the package is published, the intended installation is:

``` bash
composer require your-vendor/laravel-model-integrity --dev
```

If Laravel package auto-discovery is configured, the service provider
should register automatically.

Run:

``` bash
php artisan model-integrity:check
```

Publish configuration if the package exposes a configuration command:

``` bash
php artisan vendor:publish --tag=model-integrity-config
```

The package name, vendor name, command, and publish tag above are
provisional until finalized in code.

### Local package development

For local development, use Composer path repositories in a test Laravel
application. Keep the package repository separate from the sample
application so tests reflect a real consumer installation.

The package should also be testable with fixtures and should not require
a full application database just to run static analysis.

------------------------------------------------------------------------

## 15. Documentation Requirements

The public README should contain:

1.  What the package does and does not do.
2.  Supported PHP and Laravel versions.
3.  Installation instructions.
4.  Basic command usage.
5.  Configuration reference.
6.  Rule catalog with severity defaults.
7.  Example reports.
8.  Exit-code behavior and CI integration.
9.  Known limitations of static analysis.
10. Contribution and testing instructions.
11. License and release policy.

Document that "no finding" does not prove all runtime behavior is
correct. Static analysis can only verify what it can resolve from source
code.

------------------------------------------------------------------------

## 16. Definition of Done for Version 1

Version 1 is ready when:

-   [ ] A compatible Laravel app can install the package through
    Composer.
-   [x] The Artisan command is registered and documented.
-   [ ] Model, factory, and seeder discovery works for configured paths.
-   [ ] Factory checks are implemented and tested.
-   [ ] Seeder checks are implemented and tested.
-   [ ] Core relationship checks are implemented and tested.
-   [x] Console reports contain stable rule IDs, severity, location, and
    remediation.
-   [ ] JSON output is valid and documented.
-   [ ] Exit codes work for CI.
-   [ ] Configuration is validated.
-   [ ] The scan does not execute seeders or mutate the database.
-   [ ] Compatibility claims match the automated test matrix.
-   [ ] README, changelog, license, and contribution guidance are
    present.

------------------------------------------------------------------------

## 17. Next Implementation Slice

The first local slice is complete: package metadata, the service
provider and command, `Finding` and `Report`, AST parsing, source
fixtures, and `factory.model_not_found` with tests. Keep extending it
in small, reviewable steps.

1.  Verify installation and command registration in a separate Laravel
    12 application before claiming consumer compatibility.
2.  Add convention-based model-to-factory candidate resolution with
    tests for valid mappings, plural names, and explicit overrides.
3.  Add further factory rules only after their false-positive behavior
    is defined and tested.
4.  Move to seeder analysis after factory checks are reliable, then to
    relationship analysis.

The first milestone remains an initial local implementation until the
consumer installation check is complete.
