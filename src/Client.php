<?php declare(strict_types=1);
namespace FreeMobile;

use Psr\Http\Message\{UriInterface};
use Symfony\Component\EventDispatcher\{EventDispatcher};
use Symfony\Component\HttpClient\{Psr18Client};

/** Sends messages by SMS to a Free Mobile account. */
class Client extends EventDispatcher {

  /** @var string An event that is triggered when a request is made to the remote service. */
  const eventRequest = RequestEvent::class;

  /** @var string An event that is triggered when a response is received from the remote service. */
  const eventResponse = ResponseEvent::class;

  /** @var UriInterface The URL of the API end point. */
  private UriInterface $endPoint;

  /** @var Psr18Client The HTTP client. */
  private Psr18Client $http;

  /** @var string The identification key associated to the account. */
  private string $password;

  /** @var string The user name associated to the account. */
  private string $username;

  /**
   * Creates a new client.
   * @param string $username The user name associated to the account.
   * @param string $password The identification key associated to the account.
   * @param UriInterface|null $endPoint The URL of the API end point.
   */
  function __construct(string $username, string $password, ?UriInterface $endPoint = null) {
    assert(mb_strlen($username) > 0);
    assert(mb_strlen($password) > 0);
    parent::__construct();

    $this->http = new Psr18Client;
    $this->endPoint = $endPoint ?? $this->http->createUri('https://smsapi.free-mobile.fr/');
    $this->password = $password;
    $this->username = $username;
  }

  /**
   * Gets the URL of the API end point.
   * @return UriInterface The URL of the API end point.
   */
  function getEndPoint(): UriInterface {
    return $this->endPoint;
  }

  /**
   * Gets the identification key associated to the account.
   * @return string The identification key associated to the account.
   */
  function getPassword(): string {
    return $this->password;
  }

  /**
   * Gets the user name associated to the account.
   * @return string The user name associated to the account.
   */
  function getUsername(): string {
    return $this->username;
  }

  /**
   * Sends a SMS message to the underlying account.
   * @param string $text The text of the message to send.
   * @throws ClientException An error occurred while sending the message.
   */
  function sendMessage(string $text): void {
    assert(mb_strlen($text) > 0);

    $endPoint = $this->getEndPoint();
    $uri = $endPoint->withPath("{$endPoint->getPath()}sendmsg")->withQuery(http_build_query([
      'msg' => mb_substr(trim($text), 0, 160),
      'pass' => $this->getPassword(),
      'user' => $this->getUsername()
    ], '', '&', PHP_QUERY_RFC3986));

    try {
      $request = $this->http->createRequest('GET', $uri);
      $this->dispatch(new RequestEvent($request));

      $response = $this->http->sendRequest($request);
      $this->dispatch(new ResponseEvent($response, $request));
    }

    catch (\Throwable $e) {
      throw new ClientException($e->getMessage(), $uri, $e);
    }
  }
}
