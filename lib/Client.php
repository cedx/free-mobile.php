<?php
/**
 * Implementation of the `freemobile\Client` class.
 */
namespace freemobile;

use GuzzleHttp\{Client as HTTPClient};
use Rx\{Observable, ObserverInterface};

/**
 * Sends messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 */
class Client {

  /**
   * @var string The URL of the API end point.
   */
  const END_POINT = 'https://smsapi.free-mobile.fr/sendmsg';

  /**
   * @var string The identification key associated to the account.
   */
  private $password;

  /**
   * @var string The user name associated to the account.
   */
  private $username;

  /**
   * Initializes a new instance of the class.
   * @param string $username The user name associated to the account.
   * @param string $password The identification key associated to the account.
   * @throws \InvalidArgumentException The specified user name or password is empty.
   */
  public function __construct(string $username, string $password) {
    if (!mb_strlen($username)) throw new \InvalidArgumentException('The specified user name is empty.');
    $this->username = $username;

    if (!mb_strlen($password)) throw new \InvalidArgumentException('The specified password is empty.');
    $this->password = $password;
  }

  /**
   * Sends a SMS message to the underlying account.
   * @param string $text The text of the message to send.
   * @return Observable The response as string.
   */
  public function sendMessage(string $text): Observable {
    $encoded = trim(mb_convert_encoding($text, 'ISO-8859-1'));
    if (!strlen($encoded)) return Observable::error(new \InvalidArgumentException('The specified message is empty.'));

    return Observable::create(function(ObserverInterface $observer) use($encoded) {
      try {
        $promise = (new HTTPClient())->getAsync(static::END_POINT, ['query' => [
          'msg' => substr($encoded, 0, 160),
          'pass' => $this->password,
          'user' => $this->username
        ]]);

        $response = $promise->then()->wait();
        $observer->onNext((string) $response->getBody());
        $observer->onCompleted();
      }

      catch(\Exception $e) {
        $observer->onError($e);
      }
    });
  }
}
