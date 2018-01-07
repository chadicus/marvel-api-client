<?php

namespace Chadicus\Marvel\Api\Cache;

use Psr\SimpleCache\CacheInterface;

/**
 * A PSR-16 implementation which does not save or store any data.
 */
final class NullCache implements CacheInterface
{
    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     */
    public function get($key, $default = null)//@codingStandardsIgnoreLine  Interface does not define type-hints
    {
        return $default;
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                    $key   The key of the item to store.
     * @param mixed                     $value The value of the item to store, must be serializable.
     * @param null|integer|DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                         the driver supports TTL then the library may set a default value
     *                                         for it or let the driver take care of that.
     *
     * @return boolean True on success and false on failure.
     */
    public function set($key, $value, $ttl = null)//@codingStandardsIgnoreLine  Interface does not define type-hints
    {
        return true;
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return boolean True if the item was successfully removed. False if there was an error.
     */
    public function delete($key)//@codingStandardsIgnoreLine  Interface does not define type-hints
    {
        return true;
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return boolean True on success and false on failure.
     */
    public function clear()
    {
        return true;
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can obtained in a single operation.
     * @param mixed    $default Default value to return for keys that do not exist.
     *
     * @return array A list of key => value pairs. Keys that do not exist or are stale will have $default as value.
     */
    public function getMultiple($keys, $default = null)//@codingStandardsIgnoreLine  Interface does not define type-hints
    {
        $items = [];
        foreach ($keys as $key) {
            $items[$key] = $default;
        }

        return $items;
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable                  $values A list of key => value pairs for a multiple-set operation.
     * @param null|integer|DateInterval $ttl    Optional. The TTL value of this item. If no value is sent and
     *                                          the driver supports TTL then the library may set a default value
     *                                          for it or let the driver take care of that.
     *
     * @return boolean True on success and false on failure.
     */
    public function setMultiple($values, $ttl = null)//@codingStandardsIgnoreLine  Interface does not define type-hints
    {
        return true;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return boolean True if the items were successfully removed. False if there was an error.
     */
    public function deleteMultiple($keys)//@codingStandardsIgnoreLine  Interface does not define type-hints
    {
        return true;
    }

    /**
     * Determines whether an item is present in the cache.
     *
     * NOTE: It is recommended that has() is only to be used for cache warming type purposes
     * and not to be used within your live applications operations for get/set, as this method
     * is subject to a race condition where your has() will return true and immediately after,
     * another script can remove it making the state of your app out of date.
     *
     * @param string $key The cache item key.
     *
     * @return boolean
     */
    public function has($key)//@codingStandardsIgnoreLine  Interface does not define type-hints
    {
        return false;
    }
}
