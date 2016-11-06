<?php
/**
 * Implementation of the `freemobile\test\ClientTest` class.
 */
namespace freemobile\test;
use freemobile\{Client};

/**
 * Tests the features of the `freemobile\Client` class.
 */
class ClientTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var Client The data context of the tests.
   */
  private $model;

  /**
   * Performs a common set of tasks just before each test method is called.
   */
  protected function setUp() {
    $this->model = new Client(getenv('FREEMOBILE_USERNAME'), getenv('FREEMOBILE_PASSWORD'));
  }
}
