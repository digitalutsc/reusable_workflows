<?php

namespace Drupal\Tests\passing_tests\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Kernel tests that always pass.
 *
 * @group passing_tests
 */
class PassingKernelTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['passing_tests'];

  /**
   * Always passing kernel test.
   */
  public function testSomething() {
    $this->assertTrue(TRUE);
  }

}
