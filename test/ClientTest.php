<?php
declare(strict_types=1);
namespace FreeMobile;

use function PHPUnit\Expect\{expect, fail, it};
use PHPUnit\Framework\{TestCase};

/**
 * Tests the features of the `FreeMobile\Client` class.
 */
class ClientTest extends TestCase {

  /**
   * @test Client::sendMessage
   */
  public function testSendMessage() {
    it('should not send valid messages with invalid credentials', function() {
      try {
        (new Client('', ''))->sendMessage('Hello World!');
        fail('A message with empty credentials should not be sent.');
      }

      catch (\Throwable $e) {
        expect(true)->to->be->true;
      }
    });

    it('should not send invalid messages with valid credentials', function() {
      try {
        (new Client('anonymous', 'secret'))->sendMessage('');
        fail('A message with empty credentials should not be sent.');
      }

      catch (\Throwable $e) {
        expect(true)->to->be->true;
      }
    });

    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      it('should send valid messages with valid credentials', function() use ($password, $username) {
        try {
          (new Client($username, $password))->sendMessage('Bonjour Cédric !');
          expect(true)->to->be->true;
        }

        catch (\Throwable $e) {
          fail($e->getMessage());
        }
      });
    }
  }
}
