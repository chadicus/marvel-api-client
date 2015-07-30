<?php
namespace Chadicus\Marvel\Api;

use DominionEnterprises\Util;

/**
 * Represents a request to the Marvel API.
 */
class Request
{
    /**
     * The url for this request.
     *
     * @var string
     */
    private $url;

    /**
     * The HTTP method for this request.
     *
     * @var string
     */
    private $method;

    /**
     * The body for this request.
     *
     * @var string
     */
    private $body;

    /**
     * The HTTP headers for this request.
     *
     * @var array
     */
    private $headers;

    /**
     * Create a new instance of Request.
     *
     * @param string $url     The url of the request.
     * @param string $method  The http method of the request.
     * @param array  $headers The headers of the request.
     * @param array  $body    The body of the request.
     *
     * @throws \InvalidArgumentException Thrown if $url is not a non-empty string.
     * @throws \InvalidArgumentException Thrown if $method is not a non-empty string.
     */
    final public function __construct($url, $method, array $headers = [], array $body = [])
    {
        Util::throwIfNotType(['string' => [$url, $method]], true);
        $this->url = $url;
        $this->method = $method;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Get the url of this request.
     *
     * @return string
     */
    final public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get the method of this request.
     *
     * @return string
     */
    final public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the body of this request.
     *
     * @return string
     */
    final public function getBody()
    {
        return $this->body;
    }

    /**
     * Get the headers of this request.
     *
     * @return array
     */
    final public function getHeaders()
    {
        return $this->headers;
    }
}
