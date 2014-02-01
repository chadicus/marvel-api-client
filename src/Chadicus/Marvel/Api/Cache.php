<?php
namespace Chadicus\Marvel\Api;

/**
 * Interface for caching API responses
 */
interface Cache
{
    /**
     * Store the api $response as the cached result of the api $request.
     *
     * @param Request  $request  The request for which the response will be cached.
     * @param Response $response The reponse to cache.
     *
     * @return void
     */
    public function set(Request $request, Response $response);

    /**
     * Retrieve the cached results of the api $request.
     *
     * @param Request $request A request for which the response may be cached.
     *
     * @return Response|null
     */
    public function get(Request $request);
}
