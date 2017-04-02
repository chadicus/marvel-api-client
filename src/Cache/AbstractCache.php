<?php

namespace Chadicus\Marvel\Api\Cache;

/**
 * A PSR-16 implementation which does not save or store any data.
 */
abstract class AbstractCache
{
    /**
     * Verifies the the given cache key is a legal value.
     *
     * @param mixed $key The cache key to validate.
     *
     * @return void
     *
     * @throws InvalidArgumentException Thrown if the $key string is not a legal value.
     */
    final protected function verifyKey($key)
    {
        if (!is_string($key) || $key === '') {
            throw new InvalidArgumentException('$key must be a valid non-empty string');
        }
    }

    /**
     * Verify a given $value is an instance of PSR-7 ResponseInterface.
     *
     * @param mixed $value The PSR-7 Response to cache.
     *
     * @return void
     *
     * @throws InvalidArgumentException Thrown if $value is not a PSR-7 Response instance.
     */
    final protected function verifyValue($value)
    {
        if (!is_a($value, '\\Psr\\Http\\Message\\ResponseInterface')) {
            throw new InvalidArgumentException('$value an instance of \\Psr\\Http\\Message\\ResponseInterface');
        }
    }
}
