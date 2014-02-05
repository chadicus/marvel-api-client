<?php
/**
 * Defines the Chadicus\Marvel\Api\Response class
 */
namespace Chadicus\Marvel\Api;

/**
 * Represents a response from the Marvel API.
 */
class Response
{
    /**
     * The http status of the response.
     *
     * @var integer
     */
    private $httpCode;

    /**
     * The response body.
     *
     * @var array
     */
    private $body;

    /**
     * A array of headers received with the response.
     *
     * @var array array where each header key has an array of values
     */
    private $headers;

    /**
     * Create a new instance of Response.
     *
     * @param integer $httpCode The http response code.
     * @param array   $headers  The response headers.
     * @param array   $body     The response body.
     *
     * @throws \InvalidArgumentException Throw if $httpCode is not an integer between 100 and 600.
     */
    final public function __construct($httpCode, array $headers, array $body = [])
    {
        if ($httpCode < 100 || $httpCode > 600) {
            throw new \InvalidArgumentException('$httpCode must be an integer >= 100 and <= 600');
        }

        $this->httpCode = $httpCode;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Returns the HTTP status code of the response.
     *
     * @return integer
     */
    final public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * Returns the response body.
     *
     * @return array
     */
    final public function getBody()
    {
        return $this->body;
    }

    /**
     * Returns response headers.
     *
     * @return array
     */
    final public function getHeaders()
    {
        return $this->headers;
    }
}
