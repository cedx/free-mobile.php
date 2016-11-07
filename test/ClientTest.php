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
   * Tests the `Client` constructor.
   */
  public function testConstructor() {
    $this->expectException(\InvalidArgumentException::class);
    new Client('', '');
  }

  /**
   * Tests the `Client::sendMessage()` method.
   */
  public function testSendMessage() {
    $client = new Client('foo', 'bar');
    $this->assertInstanceOf(Observable::class, $client->sendMessage(''));

    // TODO
    $message = 'Hello World!';
  }

  /**
   * Performs a common set of tasks just before each test method is called.
   */
  protected function setUp() {
    $this->model = new Client(getenv('FREEMOBILE_USERNAME'), getenv('FREEMOBILE_PASSWORD'));
  }
}
