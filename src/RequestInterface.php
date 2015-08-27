<?php

namespace Chadicus\Marvel\Api;

/**
 * Represents a request to the Marvel API.
 */
interface RequestInterface
{
    /**
     * Get the url of this request.
     *
     * @return string
     */
    public function getUrl();

    /**
     * Get the method of this request.
     *
     * @return string
     */
    public function getMethod();

    /**
     * Get the body of this request.
     *
     * @return array
     */
    public function getBody();

    /**
     * Get the headers of this request.
     *
     * @return array
     */
    public function getHeaders();
}
