<?php declare(strict_types=1);
namespace Belin\FreeMobile;

use Uri\Rfc3986\Uri;

/**
 * Sends messages by SMS to a Free Mobile account.
 */
final readonly class Client {

	/**
	 * The Free Mobile account.
	 */
	public string $account;

	/**
	 * The Free Mobile API key.
	 */
	public string $apiKey;

	/**
	 * The base URL of the remote API endpoint.
	 */
	public Uri $baseUrl;

	/**
	 * Creates a new client.
	 * @param string $account The Free Mobile account.
	 * @param string $apiKey The Free Mobile API key.
	 * @param string|Uri $baseUrl The base URL of the remote API endpoint.
	 */
	function __construct(string $account, string $apiKey, string|Uri $baseUrl = "https://smsapi.free-mobile.fr") {
		$this->account = $account;
		$this->apiKey = $apiKey;
		$this->baseUrl = new Uri(mb_rtrim($baseUrl instanceof Uri ? $baseUrl->toString() : $baseUrl, "/"));
	}

	/**
	 * Sends an SMS message to the underlying account.
	 * @param string $text The message text.
	 * @throws \RuntimeException An error occurred while sending the message.
	 */
	function sendMessage(string $text): void {
		$query = ["msg" => mb_substr(mb_trim($text), 0, 160), "pass" => $this->apiKey, "user" => $this->account];
		$handle = curl_init($this->baseUrl
			->withPath("{$this->baseUrl->getPath()}/sendmsg")
			->withQuery(http_build_query($query, arg_separator: "&", encoding_type: PHP_QUERY_RFC3986))
			->toString());

		if (!$handle) throw new \RuntimeException("Unable to allocate the cURL handle.", 500);
		curl_setopt_array($handle, [CURLOPT_FOLLOWLOCATION => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_USERAGENT => "PHP/".PHP_MAJOR_VERSION]);
		if (curl_exec($handle) === false) throw new \RuntimeException(curl_error($handle), 500);

		$statusCode = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
		if (intdiv($statusCode, 100) != 2) throw new \RuntimeException("The server response failed.", $statusCode);
	}
}
