# Reusable Drupal Workflows
This repository hosts reusable workflows for Github Actions for use with Drupal modules. The workflows can be found in `.github/workflows`.

The rest of the repository is dedicated to a custom Drupal module (`passing_tests`) that the `Self-test` workflow (`testing.yml`) runs through `islandora-module-ci.yml` on every push and pull request to `main`.

## Usage
Please refer to [Github's official documentation for reusable workflows](https://docs.github.com/en/actions/using-workflows/reusing-workflows).

```yaml
name: CI

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]
  workflow_dispatch:

permissions:
  contents: read

jobs:
  islandora-module-ci:
    uses: digitalutsc/reusable_workflows/.github/workflows/islandora-module-ci.yml@main
    with:
      module_name: my_module
```

Set `branches` to the branch you release from. `@main` follows every change to this repository; pin a tag or commit SHA to control when a module picks up changes.

## Workflows
### islandora-module-ci.yml
Runs the module in the `ghcr.io/islandora/ci` image (the same image Islandora's own modules use): the module is mounted read-only at `web/modules/contrib/<module_name>`, enabled, and its tests are run. One job per Drupal × PHP combination.

#### Parameters
* `module_name` (Required) Machine name of the module (the name of its `.info.yml` file).
* `install_chromedriver` (Optional, default `false`) Start a Chromedriver container. Only `FunctionalJavascript` tests need it; `Unit`, `Kernel` and `Functional` tests don't.
* `drupal_versions` (Optional, default `'["10.6", "11.3"]'`) JSON array of Drupal minor versions.
* `php_versions` (Optional, default `'["8.3", "8.4"]'`) JSON array of PHP versions.
* `timeout_minutes` (Optional, default `30`) Timeout for each job.

Every Drupal × PHP pair must exist as a `ghcr.io/islandora/ci:<drupal>-php<php>` tag.

#### Requirements
* Tests under `tests/src/Unit`, `tests/src/Kernel`, `tests/src/Functional` or `tests/src/FunctionalJavascript`. A module without tests still gets a green check, which then means only that the image's checks passed.
* Nothing installs the module's Composer dependencies: every module it depends on must already be in the image.

### islandora-module-ci-d10only.yml (deprecated)
Calls `islandora-module-ci.yml` with `drupal_versions: '["10.6"]'`. Use `islandora-module-ci.yml` with `drupal_versions` instead.

### ci-testing.yml (deprecated)
The previous workflow (PHP-CS, Drupal Check and PHPUnit on a Drupal codebase built with `digitalutsc/islandora_ci`). No longer maintained or self-tested; it targets PHP 8.1 / Drupal 10.1 and uses `actions/cache@v2`, which GitHub has retired.

#### Acknowledgements
`ci-testing.yml` is based on Islandora's implementation of similar tests.
