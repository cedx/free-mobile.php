<?php declare(strict_types=1);
namespace FreeMobile;

use Psr\Http\Message\UriInterface;
use Symfony\Component\HttpClient\Psr18Client;

/**
 * Sends messages by SMS to a Free Mobile account.
 */
class Client {

	/**
	 * The Free Mobile account.
	 * @var string
	 */
	public readonly string $account;

	/**
	 * The Free Mobile API key.
	 * @var string
	 */
	public readonly string $apiKey;

	/**
	 * The base URL of the remote API endpoint.
	 * @var UriInterface
	 */
	public readonly UriInterface $baseUrl;

	/**
	 * The underlying HTTP client.
	 * @var Psr18Client
	 */
	private Psr18Client $http;

	/**
	 * Creates a new client.
	 * @param string $account The Free Mobile account.
	 * @param string $apiKey The Free Mobile API key.
	 * @param string|null $baseUrl The base URL of the remote API endpoint.
	 */
	function __construct(string $account, string $apiKey, ?string $baseUrl = null) {
		$this->account = $account;
		$this->apiKey = $apiKey;
		$this->baseUrl = $this->http->createUri($baseUrl ?? "https://smsapi.free-mobile.fr/");
		$this->http = new Psr18Client;
	}

	/** Gets the URL of the API end point. */
	function getEndPoint(): UriInterface {
		return $this->baseUrl;
	}

	/** Gets the identification key associated to the account. */
	function getPassword(): string {
		return $this->apiKey;
	}

	/** Gets the user name associated to the account. */
	function getUsername(): string {
		return $this->account;
	}

	/**
	 * Sends a SMS message to the underlying account.
	 * @throws ClientException An error occurred while sending the message.
	 */
	function sendMessage(string $text): void {
		$baseUrl = $this->getEndPoint();
		$uri = $baseUrl->withPath("{$baseUrl->getPath()}sendmsg")->withQuery(http_build_query([
			"msg" => mb_substr(trim($text), 0, 160),
			"pass" => $this->getPassword(),
			"user" => $this->getUsername()
		], "", "&", PHP_QUERY_RFC3986));

		try {
			$request = $this->http->createRequest("GET", $uri);
			$this->dispatch(new RequestEvent($request));

			$response = $this->http->sendRequest($request);
			$this->dispatch(new ResponseEvent($response, $request));
		}

		catch (\Throwable $e) {
			throw new ClientException($e->getMessage(), $uri, $e);
		}
	}
}
