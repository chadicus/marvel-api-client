<?php

namespace Chadicus\Marvel\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Request;

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
     * Guzzle HTTP Client implementation.
     *
     * @var GuzzleClientInterface
     */
    private $guzzleClient;

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
     * @param string                $privateApiKey The private api key issued by Marvel.
     * @param string                $publicApiKey  The public api key issued by Marvel.
     * @param GuzzleClientInterface $guzzleClient  Implementation of a Guzzle HTTP client.
     * @param Cache\CacheInterface  $cache         Implementation of Cache.
     */
    final public function __construct(
        string $privateApiKey,
        string $publicApiKey,
        GuzzleClientInterface $guzzleClient = null,
        Cache\CacheInterface $cache = null
    ) {
        $this->privateApiKey = $privateApiKey;
        $this->publicApiKey = $publicApiKey;
        $this->guzzleClient = $guzzleClient ?: new GuzzleClient();
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
    final public function search(string $resource, array $filters = [])
    {
        $filters['apikey'] = $this->publicApiKey;
        $timestamp = time();
        $filters['ts'] = $timestamp;
        $filters['hash'] = md5($timestamp . $this->privateApiKey . $this->publicApiKey);
        $url = self::BASE_URL . urlencode($resource) . '?' . http_build_query($filters);

        return $this->send(new Request($url, 'GET', 'php://temp', ['Accept' =>  'application/json']));
    }

    /**
     * Execute a GET request against the Marvel API for a single resource.
     *
     * @param string  $resource The API resource to search for.
     * @param integer $id       The id of the API resource.
     *
     * @return ResponseInterface
     */
    final public function get(string $resource, int $id)
    {
        $timestamp = time();
        $query = [
            'apikey' => $this->publicApiKey,
            'ts' => $timestamp,
            'hash' => md5($timestamp . $this->privateApiKey . $this->publicApiKey),
        ];

        $url = self::BASE_URL . urlencode($resource) . "/{$id}?" . http_build_query($query);

        return $this->send(new Request($url, 'GET', 'php://temp', ['Accept' =>  'application/json']));
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

        $response = $this->guzzleClient->send($request);

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

    /**
     * Allow calls such as $client->characters();
     *
     * @param string $name      The name of the api resource.
     * @param array  $arguments The parameters to pass to get() or search().
     *
     * @return Collection|EntityInterface
     */
    final public function __call(string $name, array $arguments)
    {
        $resource = strtolower($name);
        $parameters = array_shift($arguments);
        if ($parameters === null || is_array($parameters)) {
            return new Collection($this, $resource, $parameters ?: []);
        }

        $response = $this->get($resource, $parameters);
        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $json = (string)$response->getBody();
        $dataWrapper = new DataWrapper(json_decode($json, true));
        $results = $dataWrapper->getData()->getResults();
        if (empty($results)) {
            return null;
        }

        return $results[0];
    }
}
