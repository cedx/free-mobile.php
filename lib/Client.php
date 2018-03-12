<?php
declare(strict_types=1);
namespace FreeMobile;

use Evenement\{EventEmitterInterface, EventEmitterTrait};
use GuzzleHttp\{Client as HTTPClient};
use GuzzleHttp\Psr7\{Request, Uri};
use Psr\Http\Message\{UriInterface};

/**
 * Sends messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 */
class Client implements EventEmitterInterface {
  use EventEmitterTrait;

  /**
   * @var string An event that is triggered when a request is made to the remote service.
   */
  public const EVENT_REQUEST = 'request';

  /**
   * @var string An event that is triggered when a response is received from the remote service.
   */
  public const EVENT_RESPONSE = 'response';

  /**
   * @var Uri The URL of the API end point.
   */
  private $endPoint;

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
   * @param string|UriInterface $endPoint The URL of the API end point.
   * @throws \InvalidArgumentException The account credentials are invalid.
   */
  public function __construct(string $username, string $password, $endPoint = 'https://smsapi.free-mobile.fr') {
    if (!mb_strlen($username) || !mb_strlen($password)) throw new \InvalidArgumentException('The account credentials are invalid');

    $this->username = $username;
    $this->password = $password;
    $this->endPoint = is_string($endPoint) ? new Uri($endPoint) : $endPoint;
  }

  /**
   * Gets the URL of the API end point.
   * @return UriInterface The URL of the API end point.
   */
  public function getEndPoint(): UriInterface {
    return $this->endPoint;
  }

  /**
   * Gets the identification key associated to the account.
   * @return string The identification key associated to the account.
   */
  public function getPassword(): string {
    return $this->password;
  }

  /**
   * Gets the user name associated to the account.
   * @return string The user name associated to the account.
   */
  public function getUsername(): string {
    return $this->username;
  }

  /**
   * Sends a SMS message to the underlying account.
   * @param string $text The text of the message to send.
   * @throws \InvalidArgumentException The specified message is empty.
   * @throws ClientException An error occurred while sending the message.
   */
  public function sendMessage(string $text): void {
    $message = trim($text);
    if (!mb_strlen($message)) throw new \InvalidArgumentException('The specified message is empty');

    $uri = $this->getEndPoint()->withPath('/sendmsg')->withQuery(http_build_query([
      'msg' => mb_substr($message, 0, 160),
      'pass' => $this->getPassword(),
      'user' => $this->getUsername()
    ]));

    try {
      $request = new Request('GET', $uri);
      $this->emit(static::EVENT_REQUEST, [$request]);

      $response = (new HTTPClient)->send($request);
      $this->emit(static::EVENT_RESPONSE, [$request, $response]);
    }

    catch (\Throwable $e) {
      throw new ClientException($e->getMessage(), $uri, $e);
    }
  }
}
