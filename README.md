# Reusable Drupal Workflows
This repository hosts reusable workflows for Github Actions for use with Drupal modules. The workflows can be found in `.github/workflows`.

The rest of the repository is dedicated to a custom Drupal module to ensure that that the Actions work properly with a module.

## Usage
Please refer to [Github's official documentation for reusable workflows](https://docs.github.com/en/actions/using-workflows/reusing-workflows).

## Workflows
### ci-testing.yml
This workflow runs PHP-CS, Drupal Check, and any tests written for an individual module on various versions of Drupal and PHP.

#### Requirements
* `composer.json` file
* If using the default `phpunit.xml`, then tests must be located under `./tests/src/Unit`, `./tests/src/Functional`, or `./tests/src/Kernel`.

#### Acknowledgements
`ci-testing.yml` is based on Islandora's implementation of similar tests.
