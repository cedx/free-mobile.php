<?php
/**
 * Implementation of the `freemobile\Client` class.
 */
namespace freemobile;

use GuzzleHttp\{Client as HTTPClient};
use GuzzleHttp\Psr7\{ServerRequest};
use Rx\{Observable, ObserverInterface};
use Rx\Subject\{Subject};

/**
 * Sends messages by SMS to a [Free Mobile](http://mobile.free.fr) account.
 */
class Client implements \JsonSerializable {

  /**
   * @var string The URL of the API end point.
   */
  const END_POINT = 'https://smsapi.free-mobile.fr/sendmsg';

  /**
   * @var Subject The handler of "request" events.
   */
  private $onRequest;

  /**
   * @var Subject The handler of "response" events.
   */
  private $onResponse;

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
   * @param string $endPoint The URL of the API end point.
   */
  public function __construct(array $config = []) {
    $this->onRequest = new Subject();
    $this->onResponse = new Subject();

    foreach ($config as $property => $value) {
      $setter = "set$property";
      if(method_exists($this, $setter)) $this->$setter($value);
    }
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
   * Gets the stream of "request" events.
   * @return Observable The stream of "request" events.
   */
  public function onRequest(): Observable {
    return $this->onRequest->asObservable();
  }

  /**
   * Gets the stream of "response" events.
   * @return Observable The stream of "response" events.
   */
  public function onResponse(): Observable {
    return $this->onResponse->asObservable();
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
        $request = (new ServerRequest('GET', static::END_POINT))->withQueryParams([
          'msg' => mb_substr($message, 0, 160),
          'pass' => $password,
          'user' => $username
        ]);

        $this->onRequest->onNext($request);
        $promise = (new HTTPClient())->sendAsync($request, ['query' => $request->getQueryParams()]);
        $response = $promise->then()->wait();
        $this->onResponse->onNext($response);

        $observer->onNext((string) $response->getBody());
        $observer->onCompleted();
      }

      catch (\Throwable $e) {
        $observer->onError($e);
      }
    });

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
