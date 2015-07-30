<?php

namespace Chadicus\Marvel\Api;

use DominionEnterprises\Util\Arrays;

/**
 * Concrete implementation of Cache using an array.
 */
final class ArrayCache implements Cache
{
    /**
     * Default time to live in seconds.
     *
     * @var integer
     */
    private $defaultTimeToLive;

    /**
     * Array containing the cached responses.
     *
     * @var array
     */
    private $cache;

    /**
     * Construct a new instance of ArrayCache.
     *
     * @param integer $defaultTimeToLive The default time to live in seconds.
     *
     * @throws \InvalidArgumentException Throw if $defaultTimeToLive is not an integer between 0 and 86400.
     */
    public function __construct($defaultTimeToLive = Cache::MAX_TTL)
    {
        if ($defaultTimeToLive < 1 || $defaultTimeToLive > 86400) {
            throw new \InvalidArgumentException('$defaultTimeToLive must be an integer >= 1 and <= ' . Cache::MAX_TTL);
        }

        $this->defaultTimeToLive = $defaultTimeToLive;
        $this->cache = [];
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

        $this->cache[$request->getUrl()] = ['response' => $response, 'expires' => time() + $timeToLive];
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
        $id = $request->getUrl();
        $cache = Arrays::get($this->cache, $id);
        if ($cache === null) {
            return null;
        }

        if ($cache['expires'] >= time()) {
            return $cache['response'];
        }

        unset($this->cache[$id]);
        return null;
    }

    /**
     * Clears this cache.
     *
     * @return void
     */
    public function clear()
    {
        $this->cache = [];
    }
}
