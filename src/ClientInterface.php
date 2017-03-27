<?php

namespace Chadicus\Marvel\Api;

/**
 * PHP Client for the Marvel API.
 */
interface ClientInterface
{
    /**
     * Execute a search request against the Marvel API.
     *
     * @param string $resource The API resource to search for.
     * @param array  $filters  Array of search criteria to use in request.
     *
     * @return DataWrapperInterface
     *
     * @throws \InvalidArgumentException Thrown if $resource is empty or not a string.
     */
    public function search(string $resource, array $filters = []);

    /**
     * Execute a GET request against the Marvel API for a single resource.
     *
     * @param string  $resource The API resource to search for.
     * @param integer $id       The id of the API resource.
     *
     * @return DataWrapperInterface
     */
    public function get(string $resource, int $id);
}
