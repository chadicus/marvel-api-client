<?php

namespace Chadicus\Marvel\Api;

use DominionEnterprises\Util;

/**
 * PHP Client for the Marvel API.
 */
class Client implements ClientInterface
{
    /**
     * The public api key issued by Marvel.
     *
     * @var string
     */
    private $publicApiKey;

    /**
     * The private api key issued by Marvel.
     *
     * @var string
     */
    private $privateApiKey;

    /**
     * Adapter implementation.
     *
     * @var Adapter\AdapterInterface
     */
    private $adapter;

    /**
     * Cache implementation.
     *
     * @var Cache\CacheInterface
     */
    private $cache;

    /**
     * The Marvel API url.
     *
     * @const string
     */
    const BASE_URL = 'http://gateway.marvel.com/v1/public/';

    /**
     * Construct a new Client.
     *
     * @param string                   $privateApiKey The private api key issued by Marvel.
     * @param string                   $publicApiKey  The public api key issued by Marvel.
     * @param Adapter\AdapterInterface $adapter       Implementation of a client adapter.
     * @param Cache\CacheInterface     $cache         Implementation of Cache.
     */
    final public function __construct(
        $privateApiKey,
        $publicApiKey,
        Adapter\AdapterInterface $adapter = null,
        Cache\CacheInterface $cache = null
    ) {
        Util::throwIfNotType(['string' => [$privateApiKey, $publicApiKey]], true);

        $this->privateApiKey = $privateApiKey;
        $this->publicApiKey = $publicApiKey;
        $this->adapter = $adapter ?: new Adapter\CurlAdapter();
        $this->cache = $cache;
    }

    /**
     * Execute a search request against the Marvel API.
     *
     * @param string $resource The API resource to search for.
     * @param array  $filters  Array of search criteria to use in request.
     *
     * @return ResponseInterface
     *
     * @throws \InvalidArgumentException Thrown if $resource is empty or not a string.
     */
    final public function search($resource, array $filters = [])
    {
        if (!is_string($resource) || trim($resource) == '') {
            throw new \InvalidArgumentException('$resource must be a non-empty string');
        }

        $filters['apikey'] = $this->publicApiKey;
        $timestamp = time();
        $filters['ts'] = $timestamp;
        $filters['hash'] = md5($timestamp . $this->privateApiKey . $this->publicApiKey);
        $url = self::BASE_URL . urlencode($resource) . '?' . http_build_query($filters);

        return $this->send(new Request($url, 'GET', ['Accept' =>  'application/json']));
    }

    /**
     * Execute a GET request against the Marvel API for a single resource.
     *
     * @param string  $resource The API resource to search for.
     * @param integer $id       The id of the API resource.
     *
     * @return ResponseInterface
     */
    final public function get($resource, $id)
    {
        Util::throwIfNotType(['string' => [$resource], 'int' => [$id]], true);

        $timestamp = time();
        $query = [
            'apikey' => $this->publicApiKey,
            'ts' => $timestamp,
            'hash' => md5($timestamp . $this->privateApiKey . $this->publicApiKey),
        ];

        $url = self::BASE_URL . urlencode($resource) . "/{$id}?" . http_build_query($query);

        return $this->send(new Request($url, 'GET', ['Accept' =>  'application/json']));
    }

    /**
     * Send the given API Request.
     *
     * @param RequestInterface $request The request to send.
     *
     * @return ResponseInterface
     */
    final private function send(RequestInterface $request)
    {
        $response = $this->getFromCache($request);
        if ($response !== null) {
            return $response;
        }

        $response = $this->adapter->send($request);

        if ($this->cache !== null) {
            $this->cache->set($request, $response);
        }

        return $response;
    }

    /**
     * Retrieve the Response for the given Request from cache.
     *
     * @param RequestInterface $request The request to send.
     *
     * @return ResponseInterface|null Returns the cached Response or null if it does not exist.
     */
    final private function getFromCache(RequestInterface $request)
    {
        if ($this->cache === null) {
            return null;
        }

        return $this->cache->get($request);
    }
}
