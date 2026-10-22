# AGENTS.md --- Laravel Model Integrity Toolkit

## 1. Start Here

Before making any change, read these files in order:

1.  `AGENTS.md` --- working rules for the coding agent.
2.  `PROJECT_SCOPE.md` --- product goals, architecture, phases, rules,
    and acceptance criteria.
3.  `README.md` and `composer.json`, if they already exist.
4.  Relevant source files and tests before editing them.

If a file does not exist yet, do not pretend it exists. Create it only
when required by the current implementation task.

## 2. Project Mission

Build a Composer-installable Laravel developer package that statically
detects likely mismatches between Eloquent Models, Factories, Seeders,
namespaces, class references, and relationships.

The first goal is a reliable, safe CLI diagnostic tool. It is not a UI
library and is not initially a code generator.

`PROJECT_SCOPE.md` is the source of truth for intended scope. If an
implementation detail is unclear, inspect the current code and tests,
then ask a focused question rather than inventing a large new feature.

## 3. Core Working Rules

-   Work in small, reviewable steps.
-   Implement one vertical slice at a time.
-   Do not implement every roadmap phase in one task.
-   Do not create large numbers of empty files or placeholder classes.
-   Do not rewrite working code unrelated to the current task.
-   Preserve existing user changes and comments unless a change is
    necessary.
-   Before editing, inspect relevant files, tests, dependency
    constraints, and repository status when available.
-   Explain important assumptions and compatibility decisions.
-   Prefer straightforward, maintainable PHP over unnecessary
    abstractions.
-   Use clear names, strict types where appropriate, and documented
    public APIs.
-   Keep analysis logic separate from Laravel integration and output
    formatting.
-   Never claim a test, command, or compatibility check passed unless it
    was actually run successfully.

## 4. Safety Rules

The default analysis must be static.

-   Do not run migrations or seeders as part of the analyzer.
-   Do not connect to, write to, truncate, or delete application
    database data.
-   Do not execute arbitrary source files from the target project to
    discover classes.
-   Do not read or print `.env` secrets.
-   Do not modify the consumer application's files during a normal
    check.
-   Any future autofix feature must be separately scoped, opt-in,
    narrowly targeted, and tested.
-   Use temporary test fixtures and isolated test environments where
    possible.

## 5. Architecture Boundaries

Follow the architecture in `PROJECT_SCOPE.md`, adding files only as
needed.

-   **Discovery:** locate candidate files and declarations.
-   **Parsing:** extract source facts from PHP syntax without executing
    application code.
-   **Resolution:** resolve namespaces, imports, class references, and
    Laravel naming conventions.
-   **Analysis rules:** evaluate indexed facts and produce findings.
-   **Reporting:** format findings for console or JSON output.
-   **Laravel integration:** register the service provider, command, and
    configuration.
-   **Tests:** verify valid cases, invalid cases, and uncertain/dynamic
    cases.

Use a PHP AST parser, such as `nikic/php-parser`, rather than regular
expressions as the primary way to interpret PHP syntax. Regular
expressions may be useful for narrow supporting tasks but must not be
the core parser.

Do not treat short class names as globally unique. Resolve fully
qualified names, namespaces, and imports.

## 6. Implementation Order

Follow this order unless the user explicitly changes priorities:

1.  Package foundation and supported PHP/Laravel version policy.
2.  Minimal service provider and Artisan command.
3.  Test setup and a passing command-registration test.
4.  Finding/report data structures.
5.  Model and factory discovery.
6.  Factory-to-model resolution and the first tested mismatch rule.
7.  Remaining factory rules.
8.  Seeder discovery and reference checks.
9.  Relationship analysis.
10. JSON output, stable exit codes, and CI documentation.
11. Baseline, optional fixes, and other extensions only after the core
    is reliable.

Do not start seeder or relationship analysis before the initial factory
analysis works and has tests.

## 7. Required Workflow for Each Task

For each implementation request:

1.  Read the relevant scope section and existing code.
2.  State the exact task and expected behavior briefly.
3.  Identify the smallest set of files needed.
4.  Implement the change.
5.  Add or update tests for expected behavior and edge cases.
6.  Run the relevant tests and code-quality checks that are available.
7.  Inspect the diff for unrelated changes and accidental generated
    files.
8.  Report:
    -   What changed.
    -   Files created or modified.
    -   Commands/tests actually run and their results.
    -   Known limitations or next steps.

If a check cannot be run, state why. Do not silently skip it or report
it as passing.

## 8. Testing Requirements

-   Add tests for every analysis rule.
-   Include both valid and invalid examples.
-   Include plural and irregular model names.
-   Include explicit factory model mappings.
-   Include namespace/import mismatches and duplicate short class names.
-   Include custom relationship methods to guard against false
    positives.
-   Include traits, inheritance, and dynamic-code cases where relevant.
-   Verify console output, JSON validity, severity thresholds, and exit
    codes as those features are implemented.
-   Do not require a production database for static-analysis tests.
-   Prefer deterministic fixtures over environment-dependent behavior.

Run the repository's configured test command. Use the actual scripts and
dependency versions in `composer.json`; do not assume a command exists.

## 9. Findings and False-Positive Policy

Every finding should have a stable rule ID, severity, message, file path
when known, line number when known, and a useful suggestion.

Use severity levels consistently:

-   `error`: high-confidence invalid or unresolved reference.
-   `warning`: likely issue requiring review.
-   `info`: convention difference or low-risk observation.

Do not label every model without a factory as an error. Some models
intentionally do not need factories.

Do not assume a plural model name is wrong. Understand Laravel's
conventional factory discovery and explicit mappings before reporting a
mismatch.

Do not report dynamic or unresolvable behavior as a proven error.
Explain static-analysis limits and lower the severity when evidence is
incomplete.

## 10. Dependencies and Compatibility

-   Keep runtime dependencies minimal.
-   Keep testing, formatting, and static-analysis tools in
    `require-dev`.
-   Confirm the chosen PHP and Laravel version ranges before adding
    constraints.
-   Do not claim compatibility with a version unless dependencies and
    tests support it.
-   Avoid adding a dependency when the current implementation does not
    need it.
-   Update `composer.json`, lockfiles when appropriate, and
    documentation consistently.

## 11. Documentation

Update documentation when public behavior changes.

Document command options, configuration keys, rule IDs, severity
defaults, exit codes, compatibility, and known limitations. Ensure
examples match implemented behavior. Mark proposed features as planned
rather than implemented.

## 12. Scope Control

Do not add a web dashboard, UI component library, VS Code extension,
automatic renaming, or broad code generation unless explicitly
requested.

Do not implement future roadmap features just because they appear in
`PROJECT_SCOPE.md`. Work only on the current milestone.

If the scope document conflicts with the current user request, follow
the latest explicit user instruction and mention any important impact on
the roadmap.

## 13. Current Milestone

The package foundation, Artisan command, PHPUnit setup, AST-based class
discovery, `factory.model_not_found`, `factory.model_mapping_suspicious`,
and parse-error reporting are implemented. Convention mapping resolves
direct and inherited `HasFactory` use and respects `UseFactory` on the
model plus inherited `$factory` and `newFactory()` overrides.

For the next implementation task, verify installation in a separate
Laravel 12 application, then add one remaining factory rule in a tested
slice. Do not flag an intentionally absent factory as an error. Keep
custom-trait `HasFactory`, vendor parent chains, custom factory name
resolvers, and dynamic targets unresolved until their safeguards are
tested. Do not start seeder or relationship rules until factory analysis
is reliable.
