<?php

namespace Chadicus\Marvel\Api\Cache;

use GuzzleHttp\Psr7\Response;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * A PSR-16 implementation which stores data in an array.
 */
final class MongoCache extends AbstractCache implements CacheInterface
{
    /**
     * MongoDB collection containing the cached responses.
     *
     * @var Collection
     */
    private $collection;

    /**
     * Construct a new instance of MongoCache.
     *
     * @param Collection $collection        The collection containing the cached data.
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
        $this->collection->createIndex(['expires' => 1], ['expireAfterSeconds' => 0, 'background' => true]);
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     *
     * @throws InvalidArgumentException Thrown if the $key string is not a legal value.
     */
    public function get($key, $default = null)
    {
        $this->verifyKey($key);
        $cached = $this->collection->findOne(
            ['_id' => $key],
            ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
        );

        if ($cached === null) {
            return $default;
        }

        return new Response(
            $cached['statusCode'],
            $cached['headers'],
            $cached['body'],
            $cached['protocolVersion'],
            $cached['reasonPhrase']
        );
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                $key   The key of the item to store.
     * @param mixed                 $value The value of the item to store, must be serializable.
     * @param null|int|DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                     the driver supports TTL then the library may set a default value
     *                                     for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws InvalidArgumentException Thrown if the $key string is not a legal value.
     */
    public function set($key, $value, $ttl = null)
    {
        $this->verifyKey($key);
        $this->verifyValue($value);
        return $this->updateCache($key, $value, $this->getExpires($ttl));
    }

    /**
     * Delete an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     *
     * @throws InvalidArgumentException Thrown if the $key string is not a legal value.
     */
    public function delete($key)
    {
        $this->verifyKey($key);
        try {
            $this->collection->deleteOne(['_id' => $key]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear()
    {
        try {
            $this->collection->deleteMany([]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can obtained in a single operation.
     * @param mixed    $default Default value to return for keys that do not exist.
     *
     * @return array A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     *
     * @throws InvalidArgumentException Thrown if the $key string is not a legal value.
     */
    public function getMultiple($keys, $default = null)
    {
        array_walk($keys, [$this, 'verifyKey']);

        $items = array_fill_keys($keys, $default);
        $cached = $this->collection->find(
            ['_id' => ['$in' => $keys]],
            ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
        );

        foreach ($cached as $item) {
            $items[$item['_id']] = new Response(
                $item['statusCode'],
                $item['headers'],
                $item['body'],
                $item['protocolVersion'],
                $item['reasonPhrase']
            );
        }

        return $items;
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable              $values A list of key => value pairs for a multiple-set operation.
     * @param null|int|DateInterval $ttl    Optional. The TTL value of this item. If no value is sent and
     *                                      the driver supports TTL then the library may set a default value
     *                                      for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     *
     * @throws InvalidArgumentException Thrown if $values is neither an array nor a Traversable,
     *                                  or if any of the $values are not a legal value.
     */
    public function setMultiple($values, $ttl = null)
    {
        foreach ($values as $key => $value) {
            $this->verifyKey($key);
            $this->verifyValue($value);
            $expires = $this->getExpires($ttl);
            if (!$this->updateCache($key, $value, $expires)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     *
     * @throws InvalidArgumentException Thrown if $keys is neither an array nor a Traversable,
     *                                  or if any of the $keys are not a legal value.
     */
    public function deleteMultiple($keys)
    {
        array_walk($keys, [$this, 'verifyKey']);

        try {
            $this->collection->deleteMany(['_id' => ['$in' => $keys]]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
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
     * @return bool
     *
     * @throws InvalidArgumentException Thrown if the $key string is not a legal value.
     */
    public function has($key)
    {
        $this->verifyKey($key);
        return $this->collection->count(['_id' => $key]) === 1;
    }

    /**
     * Upserts a PSR-7 response in the cache.
     *
     * @param string $key The key of the response to store.
     * @param ResponseInterface $response The response to store.
     * @param UTCDateTime $expires The expire date of the cache item.
     *
     * @return bool
     */
    private function updateCache(string $key, ResponseInterface $response, UTCDateTime $expires) : bool
    {
        $document = [
            '_id' => $key,
            'statusCode' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => (string)$response->getBody(),
            'protocolVersion' => $response->getProtocolVersion(),
            'reasonPhrase' => $response->getReasonPhrase(),
            'expires' => $expires,
        ];

        try {
            $this->collection->updateOne(['_id' => $key], ['$set' => $document], ['upsert' => true]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Converts the given time to live value to a UTCDateTime instance;
     *
     * @param mixed $ttl The time-to-live value to validate.
     *
     * @return UTCDateTime
     *
     * @throws InvalidArgumentException Thrown if the $ttl is not null, an integer or \DateInterval.
     */
    private function getExpires($ttl)
    {
        $ttl = $ttl ?: 86400;

        if ($ttl instanceof \DateInterval) {
            return new UTCDateTime((new \DateTime('now'))->add($ttl)->getTimestamp() * 1000);
        }

        if (is_int($ttl)) {
            return new UTCDateTime((time() + $ttl) * 1000);
        }

        throw new InvalidArgumentException('$ttl must be null, an integer or \DateInterval instance');
    }
}
