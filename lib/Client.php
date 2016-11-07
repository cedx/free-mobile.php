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
  private $userName;

  /**
   * Initializes a new instance of the class.
   * @param string $userName The user name associated to the account.
   * @param string $password The identification key associated to the account.
   * @throws \InvalidArgumentException The specified user name or password is empty.
   */
  public function __construct(string $userName, string $password) {
    if (!mb_strlen($userName)) throw new \InvalidArgumentException('$userName');
    $this->userName = $userName;

    if (!mb_strlen($password)) throw new \InvalidArgumentException('$password');
    $this->password = $password;
  }

  /**
   * Sends a SMS message to the underlying account.
   * @param string $text The text of the message to send.
   * @return Observable Completes when the message has been sent.
   */
  public function sendMessage(string $text): Observable {
    $encoded = mb_convert_encoding($text, 'ISO-8859-1');
    if (!mb_strlen($encoded)) return Observable::error(new \InvalidArgumentException('$text'));

    return Observable::create(function(ObserverInterface $observer) use($encoded) {
      try {
        $promise = (new HTTPClient())->getAsync(static::END_POINT, ['query' => [
          'msg' => substr($encoded, 0, 160),
          'pass' => $this->password,
          'user' => $this->userName
        ]]);

        $promise->then(
          function($response) { var_dump($response); },
          function($error) { var_dump($error); }
        )->wait();

        // TODO: $promise->then()->wait();
        $observer->onCompleted();
      }

      catch(\Exception $e) {
        $observer->onError($e);
      }
    });
  }
}
