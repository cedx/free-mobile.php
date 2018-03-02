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
   * @test Client::__construct
   */
  public function testConstructor(): void {
    it('should throw an exception if the credentials are invalid', function() {
      try {
        (new Client('', ''))->sendMessage('Hello World!');
        fail('A message with empty credentials should not be sent');
      }

      catch (\Throwable $e) {
        expect($e)->to->be->an->instanceOf(\InvalidArgumentException::class);
      }
    });
  }

  /**
   * @test Client::sendMessage
   */
  public function testSendMessage(): void {
    it('should not send invalid messages', function() {
      try {
        (new Client('anonymous', 'secret'))->sendMessage('');
        fail('An empty message with valid credentials should not be sent');
      }

      catch (\Throwable $e) {
        expect($e)->to->be->an->instanceOf(\InvalidArgumentException::class);
      }
    });

    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      it('should send valid messages with valid credentials', function() use ($password, $username) {
        try {
          (new Client($username, $password))->sendMessage('Bonjour Cédric !');
          expect(true)->to->be->true;
        }

        catch (\Throwable $e) {
          expect($e)->to->be->an->instanceOf(ClientException::class);
        }
      });
    }
  }
}
