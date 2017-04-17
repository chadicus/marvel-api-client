<?php

namespace Chadicus\Marvel\Api;

/**
 * Interface for api data container which displays pagination information and an array of results returned by
 * an API call.
 */
interface DataContainerInterface
{
    /**
     * Returns The requested offset (number of skipped results) of the call.
     *
     * @return integer
     */
    public function getOffset() : int;

    /**
     * Returns The requested result limit.
     *
     * @return integer
     */
    public function getLimit() : int;

    /**
     * Returns The total number of resources available given the current filter set.
     *
     * @return integer
     */
    public function getTotal() : int;

    /**
     * Returns The total number of results returned by this call.
     *
     * @return integer
     */
    public function getCount() : int;

    /**
     * Returns The list of creators returned by the call.
     *
     * @return EntityInterface[]
     */
    public function getResults() : array;
}
