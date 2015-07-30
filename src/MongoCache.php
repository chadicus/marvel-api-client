<?php

namespace Chadicus\Marvel\Api;

/**
 * Concrete implementation of Cache using an array.
 */
final class MongoCache implements Cache
{
    /**
     * Default time to live in seconds.
     *
     * @var integer
     */
    private $defaultTimeToLive;

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
    public function __construct(\MongoCollection $collection, $defaultTimeToLive = Cache::MAX_TTL)
    {
        if (!extension_loaded('mongo')) {
            throw new \RuntimeException('The mongo extension is required for MongoCache');
        }

        if ($defaultTimeToLive < 1 || $defaultTimeToLive > 86400) {
            throw new \InvalidArgumentException('$defaultTimeToLive must be an integer >= 1 and <= ' . Cache::MAX_TTL);
        }

        $this->defaultTimeToLive = $defaultTimeToLive;
        $this->collection = $collection;
        $this->collection->ensureIndex(['expires' => 1], ['expireAfterSeconds' => 0, 'background' => true]);
    }

    /**
     * Store the api $response as the cached result of the api $request.
     *
     * @param Request  $request    The request for which the response will be cached.
     * @param Response $response   The reponse to cache.
     * @param integer  $timeToLive The time in seconds that the cache should live.
     *
     * @return void
     *
     * @throws \InvalidArgumentException Throw if $timeToLive is not an integer between 0 and 86400.
     */
    public function set(Request $request, Response $response, $timeToLive = null)
    {
        if ($timeToLive === null) {
            $timeToLive = $this->defaultTimeToLive;
        }

        if ($timeToLive < 1 || $timeToLive > Cache::MAX_TTL) {
            throw new \InvalidArgumentException('$timeToLive must be an integer >= 1 and <= ' . Cache::MAX_TTL);
        }

        $id = $request->getUrl();
        $cache = [
            '_id' => $id,
            'httpCode' => $response->getHttpCode(),
            'body' => $response->getBody(),
            'headers' => $response->getHeaders(),
            'expires' => new \MongoDate(time() + $timeToLive),
        ];

        $this->collection->update(array('_id' => $id), $cache, array('upsert' => true));
    }

    /**
     * Retrieve the cached results of the api $request.
     *
     * @param Request $request A request for which the response may be cached.
     *
     * @return Response|null
     */
    public function get(Request $request)
    {
        $cached = $this->collection->findOne(['_id' => $request->getUrl()]);
        if ($cached === null) {
            return null;
        }

        return new Response($cached['httpCode'], $cached['headers'], $cached['body']);
    }
}
