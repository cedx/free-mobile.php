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
  private $password = '';

  /**
   * @var string The user name associated to the account.
   */
  private $username = '';

  /**
   * Initializes a new instance of the class.
   * @param array $config Name-value pairs that will be used to initialize the object properties.
   */
  public function __construct(array $config = []) {
    foreach ($config as $property => $value) {
      $setter = "set{$property}";
      if(method_exists($this, $setter)) $this->$setter($value);
    }
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
  final public function jsonSerialize(): \stdClass {
    return $this->toJSON();
  }

  /**
   * Sends a SMS message to the underlying account.
   * @param string $text The text of the message to send.
   * @return Observable The response as string.
   */
  public function sendMessage(string $text): Observable {
    $username = $this->getUsername();
    $password = $this->getPassword();
    if (!mb_strlen($username) || !mb_strlen($password))
      return Observable::error(new \InvalidArgumentException('The account credentials are invalid.'));

    $message = trim($text);
    if (!mb_strlen($message)) return Observable::error(new \InvalidArgumentException('The specified message is empty.'));

    return Observable::create(function(ObserverInterface $observer) use($message, $password, $username) {
      try {
        $promise = (new HTTPClient())->getAsync(static::END_POINT, ['query' => [
          'msg' => mb_substr($message, 0, 160),
          'pass' => $password,
          'user' => $username
        ]]);

        $response = $promise->then()->wait();
        $observer->onNext((string) $response->getBody());
        $observer->onCompleted();
      }

      catch (\Throwable $e) {
        $observer->onError($e);
      }
    });
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

  /**
   * Converts this object to a map in JSON format.
   * @return \stdClass The map in JSON format corresponding to this object.
   */
  public function toJSON(): \stdClass {
    $map = new \stdClass();
    $map->password = $this->getPassword();
    $map->username = $this->getUsername();
    return $map;
  }

  /**
   * Returns a string representation of this object.
   * @return string The string representation of this object.
   */
  public function __toString(): string {
    $json = json_encode($this, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    return static::class." {$json}";
  }
}
