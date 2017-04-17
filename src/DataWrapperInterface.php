<?php

namespace Chadicus\Marvel\Api;

/**
 * Interface for an API data wrapper which contains metadata about the call and a container object,
 */
interface DataWrapperInterface
{
    /**
     * Returns the HTTP status code of the returned result.
     *
     * @return integer
     */
    public function getCode() : int;

    /**
     * Returns A string description of the call status.
     *
     * @return string
     */
    public function getStatus() : string;

    /**
     * Returns the copyright notice for the returned result.
     *
     * @return string
     */
    public function getCopyright() : string;

    /**
     * Returns the attribution notice for this result
     *
     * @return string
     */
    public function getAttributionText() : string;

    /**
     * Returns an HTML representation of the attribution notice for this result.
     *
     * @return string
     */
    public function getAttributionHTML() : string;

    /**
     * Returns a digest value of the content returned by the call.
     *
     * @return string
     */
    public function getEtag() : string;

    /**
     * Returns the results returned by the call.
     *
     * @return DataContainerInterface
     */
    public function getData() : DataContainerInterface;
}
