<?php

namespace Chadicus\Marvel\Api;

/**
 * Interface for api data container which displays pagination information and an array of results returned by an API call.
 */
interface DataContainerInterface
{

    /**
     * Returns The requested offset (number of skipped results) of the call.
     *
     * @return integer
     */
    public function getOffset();

    /**
     * Returns The requested result limit.
     *
     * @return integer
     */
    public function getLimit();

    /**
     * Returns The total number of resources available given the current filter set.
     *
     * @return integer
     */
    public function getTotal();

    /**
     * Returns The total number of results returned by this call.
     *
     * @return integer
     */
    public function getCount();

    /**
     * Returns The list of creators returned by the call.
     *
     * @return EntityInterface[]
     */
    public function getResults();
}
