<?php

namespace Chadicus\Marvel\Api\Entities;

interface EntityInterface
{
    /**
     * Create a new AbstractEntity based on the given $input array
     *
     * @param array $input The data for the EntityInterface
     */
    public function __construct(array $input);

    /**
     * Create an array of new AbstractEntity based on the given $input arrays
     *
     * @param array[] $inputs The value to be filtered.
     *
     * @return EntityInterface[]
     */
    public static function fromArrays(array $inputs);

    /**
     * Create a new AbstractEntity based on the given $input array
     *
     * @param array $input The data for the AbstractEntity
     *
     * @return EntityInterface
     */
    public static function fromArray(array $input);
}
