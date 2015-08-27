<?php

namespace Chadicus\Marvel\Api;

/**
 * Represents a response from the Marvel API.
 */
interface ResponseInterface
{
    /**
     * Returns the HTTP status code of the response.
     *
     * @return integer
     */
    public function getHttpCode();

    /**
     * Returns the response body.
     *
     * @return array
     */
    public function getBody();

    /**
     * Returns response headers.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Returns the parsed API response.
     *
     * @return DataWrapperInterface
     */
    public function getDataWrapper();
}
