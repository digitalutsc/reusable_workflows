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

#### Which versions it tests
The matrix is computed on every run, so a new Drupal or PHP release needs no change here or in the modules:

* **Drupal majors:** every major that drupal.org lists as supported (`supported_branches` in `https://updates.drupal.org/release-history/drupal/current`). A major drops out when drupal.org stops supporting it.
* **Drupal minors:** the `minors_per_major` newest minors of each of those majors that have a stable release.
* **Next major:** a supported major with no stable release yet gets its newest branch (e.g. `12.0` while Drupal 12 is in beta). Its jobs are **non-blocking**: a failure shows as a warning, not a red check. It becomes blocking by itself once its first stable release is out. Turn it off with `include_next_major: false`.
* **PHP:** the lowest and the highest version that `ghcr.io/islandora/ci` is built for and that the minor's `core/composer.json` allows.
* **Image:** `ghcr.io/islandora/ci:<minor>-php<php>`. When Islandora hasn't published that tag yet (typically the next major), the job builds it from [Islandora/islandora_ci](https://github.com/Islandora/islandora_ci)'s Dockerfile, which adds a few minutes. It switches to the published image as soon as one exists.

The run's summary page shows the computed matrix.

#### Parameters
* `module_name` (Required) Machine name of the module (the name of its `.info.yml` file).
* `install_chromedriver` (Optional, default `false`) Start a Chromedriver container. Only `FunctionalJavascript` tests need it; `Unit`, `Kernel` and `Functional` tests don't.
* `minors_per_major` (Optional, default `2`) How many of the newest released minors of each supported major to test.
* `include_next_major` (Optional, default `true`) Also test the next major before its first stable release (non-blocking).
* `drupal_versions` (Optional, default empty) Override: JSON array of Drupal minors, e.g. `'["10.6"]'`. Empty means computed as above.
* `php_versions` (Optional, default empty) Override: JSON array of PHP versions, e.g. `'["8.4"]'`. Empty means the lowest and highest each minor allows.
* `repository`, `ref` (Optional, default empty) Test another repository (`owner/name`) at a branch, tag or SHA instead of the calling one. Used by the weekly run.
* `timeout_minutes` (Optional, default `30`) Timeout for each job.

#### Branch protection
Require the check named **`result`** (shown as `<caller job> / result`, e.g. `islandora-module-ci / result`), not the `PHP x | Drupal y` jobs. Their names change whenever a Drupal minor is added or dropped; `result` fails if the matrix couldn't be computed or any blocking job failed.

#### Requirements
* Tests under `tests/src/Unit`, `tests/src/Kernel`, `tests/src/Functional` or `tests/src/FunctionalJavascript`. A module without tests still gets a green check, which then means only that the image's checks passed.
* Nothing installs the module's Composer dependencies: every module it depends on must already be in the image.
* For the next major to install the module at all, its `.info.yml` `core_version_requirement` must include it (e.g. `^10 || ^11 || ^12`).
* The matrix job needs `updates.drupal.org`, `git.drupalcode.org` and `ghcr.io`. If one is down, the run fails visibly (`result` is red) instead of testing nothing.

### weekly-modules.yml
Every Monday (and on demand from the Actions tab) runs `islandora-module-ci.yml` against the release branch of every module listed in the file. Modules' own `ci.yml` only runs on push and pull request, so without this a new Drupal release goes untested in repositories nobody is working on. Add or remove modules by editing the list.

GitHub disables a scheduled workflow after 60 days without activity in its repository; merging a Dependabot pull request (below) counts as activity. If the schedule was disabled, re-enable it from the Actions tab.

### dependabot.yml
Monthly pull requests that update the actions pinned in these workflows (e.g. `actions/checkout`).

### islandora-module-ci-d10only.yml (deprecated)
Calls `islandora-module-ci.yml` with `drupal_versions: '["10.6"]'`. Use `islandora-module-ci.yml` with `drupal_versions` instead.

### ci-testing.yml (deprecated)
The previous workflow (PHP-CS, Drupal Check and PHPUnit on a Drupal codebase built with `digitalutsc/islandora_ci`). No longer maintained or self-tested; it targets PHP 8.1 / Drupal 10.1 and uses `actions/cache@v2`, which GitHub has retired.

#### Acknowledgements
`ci-testing.yml` is based on Islandora's implementation of similar tests.
