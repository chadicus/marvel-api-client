<?php

namespace Chadicus\Marvel\Api\Cache;

use Chadicus\Marvel\Api\RequestInterface;
use Chadicus\Marvel\Api\ResponseInterface;

/**
 * Interface for caching API responses
 */
interface CacheInterface
{
    /**
     * The maximum ttl for cached responses (24 hours).
     *
     * @const integer
     */
    const MAX_TTL = 86400;

    /**
     * Store the api $response as the cached result of the api $request.
     *
     * @param RequestInterface  $request    The request for which the response will be cached.
     * @param ResponseInterface $response   The reponse to cache.
     * @param integer           $timeToLive The time in seconds that the cache should live.
     *
     * @return void
     */
    public function set(RequestInterface $request, ResponseInterface $response, $timeToLive = null);

    /**
     * Retrieve the cached results of the api $request.
     *
     * @param RequestInterface $request A request for which the response may be cached.
     *
     * @return ResponseInterface|null
     */
    public function get(RequestInterface $request);
}
