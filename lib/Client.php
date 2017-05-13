<?php
namespace freemobile;

use Evenement\{EventEmitterTrait};
use GuzzleHttp\{Client as HTTPClient};
use GuzzleHttp\Psr7\{ServerRequest};

/**
 * Sends messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 */
class Client implements \JsonSerializable {
  use EventEmitterTrait;

  /**
   * @var string The URL of the default API end point.
   */
  const DEFAULT_ENDPOINT = 'https://smsapi.free-mobile.fr';

  /**
   * @var string The URL of the API end point.
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
   * @param string $endPoint The URL of the API end point.
   */
  public function __construct(string $username = '', string $password = '', string $endPoint = self::DEFAULT_ENDPOINT) {
    $this->setUsername($username);
    $this->setPassword($password);
    $this->setEndPoint($endPoint);
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $json = json_encode($this, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return static::class." $json";
  }

  /**
   * Gets the URL of the API end point.
   * @return string The URL of the API end point.
   */
  public function getEndPoint(): string {
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
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  public function jsonSerialize(): \stdClass {
    return (object) [
      'endPoint' => $this->getEndPoint(),
      'password' => $this->getPassword(),
      'username' => $this->getUsername()
    ];
  }

  /**
   * Sends a SMS message to the underlying account.
   * @param string $text The text of the message to send.
   * @throws \InvalidArgumentException The account credentials are invalid, or the specified message is empty.
   * @throws \RuntimeException An error occurred while sending the message.
   */
  public function sendMessage(string $text) {
    $username = $this->getUsername();
    $password = $this->getPassword();
    if (!mb_strlen($username) || !mb_strlen($password))
      throw new \InvalidArgumentException('The account credentials are invalid.');

    $message = trim($text);
    if (!mb_strlen($message)) throw new \InvalidArgumentException('The specified message is empty.');

    try {
      $request = (new ServerRequest('GET', $this->getEndPoint().'/sendmsg'))->withQueryParams([
        'msg' => mb_substr($message, 0, 160),
        'pass' => $password,
        'user' => $username
      ]);

      $this->emit('request', [$request]);
      $response = (new HTTPClient)->send($request, ['query' => $request->getQueryParams()]);
      $this->emit('reponse', [$response]);
    }

    catch (\Throwable $e) {
      throw new \RuntimeException('An error occurred while sending the message.');
    }
  }

  /**
   * Sets the URL of the API end point.
   * @param string $value The new URL of the API end point.
   * @return Client This instance.
   */
  public function setEndPoint(string $value) {
    $this->endPoint = $value;
    return $this;
  }

  /**
   * Sets the identification key associated to the account.
   * @param string $value The new identification key.
   * @return Client This instance.
   */
  public function setPassword(string $value): self {
    $this->password = $value;
    return $this;
  }

  /**
   * Sets the user name associated to the account.
   * @param string $value The new username.
   * @return Client This instance.
   */
  public function setUsername(string $value): self {
    $this->username = $value;
    return $this;
  }
}
