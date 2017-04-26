<?php

namespace Chadicus\Marvel\Api\Assets;

use DominionEnterprises\Util\Arrays;
use Psr\SimpleCache\CacheInterface;

/**
 * A PSR-16 implementation which stores data in an array.
 */
final class ArrayCache implements CacheInterface
{
    /**
     * Array containing the cached data.
     *
     * @var array
     */
    private $cache = [];

    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     */
    public function get($key, $default = null)//@codingStandardsIgnoreLine Interface does not define type-hints
    {
        $cache = Arrays::get($this->cache, $key);
        if ($cache === null) {
            return $default;
        }

        if ($cache['expires'] < time()) {
            unset($this->cache[$key]);
            return $default;
        }

        return $cache['response'];
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
    public function set($key, $value, $ttl = null)//@codingStandardsIgnoreLine Interface does not define type-hints
    {
        $this->cache[$key] = [
            'response' => $value,
            'expires' => $this->getExpires($ttl),
        ];

        return true;
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return boolean True if the item was successfully removed. False if there was an error.
     */
    public function delete($key)//@codingStandardsIgnoreLine Interface does not define type-hints
    {
        unset($this->cache[$key]);
        return true;
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return boolean True on success and false on failure.
     */
    public function clear()
    {
        $this->cache = [];
        return true;
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can obtained in a single operation.
     * @param mixed    $default Default value to return for keys that do not exist.
     *
     * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     */
    public function getMultiple($keys, $default = null)//@codingStandardsIgnoreLine Interface does not define type-hints
    {
        $items = [];
        foreach ($keys as $key) {
            $items[$key] = $this->get($key, $default);
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
    public function setMultiple($values, $ttl = null)//@codingStandardsIgnoreLine Interface does not define type-hints
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return boolean True if the items were successfully removed. False if there was an error.
     */
    public function deleteMultiple($keys)//@codingStandardsIgnoreLine Interface does not define type-hints
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

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
    public function has($key)//@codingStandardsIgnoreLine Interface does not define type-hints
    {
        return isset($this->cache[$key]);
    }

    /**
     * Converts the given time to live value to a epoch timestamp.
     *
     * @param mixed $ttl The time-to-live value to validate.
     *
     * @return integer
     *
     * @throws \InvalidArgumentException if $ttl is not an integer or DateInterval
     */
    private function getExpires($ttl)
    {
        if ($ttl === null) {
            return time() + 86400;
        }

        if (is_int($ttl)) {
            return time() + $ttl;
        }

        if ($ttl instanceof \DateInterval) {
            return time() + $ttl->s;
        }

        throw new \InvalidArgumentException('$ttl must be null, an integer or \DateInterval instance');
    }
}
