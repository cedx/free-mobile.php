<?php
declare(strict_types=1);
namespace FreeMobile;

use Psr\Http\Message\{RequestInterface, ResponseInterface};

/**
 * Represents the event parameter used for request events.
 */
class RequestEvent {

  /**
   * @var RequestInterface The related HTTP request.
   */
  private $request;

  /**
   * @var ResponseInterface|null The related HTTP response.
   */
  private $response;

  /**
   * Creates a new event parameter.
   * @param RequestInterface $request The related HTTP request.
   * @param ResponseInterface|null $response The related HTTP response.
   */
  function __construct(RequestInterface $request, ResponseInterface $response = null) {
    $this->request = $request;
    $this->response = $response;
  }

  /**
   * Gets the related HTTP request.
   * @return RequestInterface The related HTTP request.
   */
  function getRequest(): RequestInterface {
    return $this->request;
  }

  /**
   * Gets the related HTTP response.
   * @return RequestInterface|null The related HTTP response.
   */
  function getResponse(): ?ResponseInterface {
    return $this->response;
  }
}
