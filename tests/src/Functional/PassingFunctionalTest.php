<?php

namespace Drupal\Tests\passing_tests\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Functional tests that always pass.
 *
 * @group passing_tests
 */
class PassingFunctionalTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stable';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['passing_tests'];

  /**
   * Always passing functional test.
   */
  public function testSomething() {
    $this->assertTrue(TRUE);
  }

}
