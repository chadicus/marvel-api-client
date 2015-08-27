<?php

namespace Chadicus\Marvel\Api\Cache;

use Chadicus\Marvel\Api\Response;
use Chadicus\Marvel\Api\ResponseInterface;
use Chadicus\Marvel\Api\RequestInterface;

/**
 * Concrete implementation of Cache using an array.
 */
final class MongoCache extends AbstractCache implements CacheInterface
{
    /**
     * MongoCollection containing the cached responses.
     *
     * @var \MongoCollection
     */
    private $collection;

    /**
     * Construct a new instance of MongoCache.
     *
     * @param \MongoCollection $collection        The collection containing the cached data.
     * @param integer          $defaultTimeToLive The default time to live in seconds.
     *
     * @throws \RuntimeException Throw if mongo extension is not loaded.
     * @throws \InvalidArgumentException Throw if $defaultTimeToLive is not an integer between 0 and 86400.
     */
    public function __construct(\MongoCollection $collection, $defaultTimeToLive = CacheInterface::MAX_TTL)
    {
        if (!extension_loaded('mongo')) {
            throw new \RuntimeException('The mongo extension is required for MongoCache');
        }

        $this->setDefaultTTL($defaultTimeToLive);
        $this->collection = $collection;
        $this->collection->ensureIndex(['expires' => 1], ['expireAfterSeconds' => 0, 'background' => true]);
    }

    /**
     * Store the api $response as the cached result of the api $request.
     *
     * @param RequestInterface  $request    The request for which the response will be cached.
     * @param ResponseInterface $response   The reponse to cache.
     * @param integer  $timeToLive The time in seconds that the cache should live.
     *
     * @return void
     *
     * @throws \InvalidArgumentException Throw if $timeToLive is not an integer between 0 and 86400.
     */
    public function set(RequestInterface $request, ResponseInterface $response, $timeToLive = null)
    {
        $timeToLive = self::ensureTTL($timeToLive ?: $this->getDefaultTTL());

        $id = $request->getUrl();
        $cache = [
            '_id' => $id,
            'httpCode' => $response->getHttpCode(),
            'body' => $response->getBody(),
            'headers' => $response->getHeaders(),
            'expires' => new \MongoDate(time() + $timeToLive),
        ];

        $this->collection->update(['_id' => $id], $cache, ['upsert' => true]);
    }

    /**
     * Retrieve the cached results of the api $request.
     *
     * @param RequestInterface $request A request for which the response may be cached.
     *
     * @return ResponseInterface|null
     */
    public function get(RequestInterface $request)
    {
        $cached = $this->collection->findOne(['_id' => $request->getUrl()]);
        if ($cached === null) {
            return null;
        }

        return new Response($cached['httpCode'], $cached['headers'], $cached['body']);
    }
}
