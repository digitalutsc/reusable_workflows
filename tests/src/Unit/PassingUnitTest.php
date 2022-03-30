<?php

namespace Drupal\Tests\passing_tests\Unit;

use Drupal\Tests\UnitTestCase;

/**
 * Unit tests that always pass.
 *
 * @group passing_tests
 */
class PassingUnitTest extends UnitTestCase {

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
  }

  /**
   * Always passing unit test.
   */
  public function testSomething() {
    $this->assertTrue(TRUE);
  }

}
