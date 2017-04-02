<?php

namespace Chadicus\Marvel\Api;

use GuzzleHttp;
use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Request;

/**
 * PHP Client for the Marvel API.
 */
class Client implements ClientInterface
{
    /**
     * The default ttl for cached responses (24 hours).
     *
     * @link http://developer.marvel.com/documentation/attribution Marvel's rules for caching.
     *
     * @const integer
     */
    const MAX_TTL = 86400;

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
     * @var CacheInterface
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
     * @param string                     $privateApiKey The private api key issued by Marvel.
     * @param string                     $publicApiKey  The public api key issued by Marvel.
     * @param GuzzleHttp\ClientInterface $guzzleClient  Implementation of a Guzzle HTTP client.
     * @param CacheInterface             $cache         Implementation of Cache.
     */
    final public function __construct(
        string $privateApiKey,
        string $publicApiKey,
        GuzzleHttp\ClientInterface $guzzleClient = null,
        CacheInterface $cache = null
    ) {
        $this->privateApiKey = $privateApiKey;
        $this->publicApiKey = $publicApiKey;
        $this->guzzleClient = $guzzleClient ?: new GuzzleHttp\Client();
        $this->cache = $cache ?: new Cache\NullCache();
    }

    /**
     * Execute a search request against the Marvel API.
     *
     * @param string $resource The API resource to search for.
     * @param array  $filters  Array of search criteria to use in request.
     *
     * @return DataWrapperInterface|null
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

        $response = $this->send(new Request($url, 'GET', 'php://temp', ['Accept' =>  'application/json']));
        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return DataWrapper::fromJson((string)$response->getBody());
    }

    /**
     * Execute a GET request against the Marvel API for a single resource.
     *
     * @param string  $resource The API resource to search for.
     * @param integer $id       The id of the API resource.
     *
     * @return DataWrapperInterface|null
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

        $response =  $this->send(new Request($url, 'GET', 'php://temp', ['Accept' =>  'application/json']));
        if ($response->getStatusCode() !== 200) {
            return null;
        }

        return DataWrapper::fromJson((string)$response->getBody());
    }

    /**
     * Send the given API Request.
     *
     * @param RequestInterface $request The request to send.
     *
     * @return ResponseInterface
     */
    final private function send(RequestInterface $request) : ResponseInterface
    {
        $key = (string)$request->getUri();
        $response = $this->cache->get($key);
        if ($response !== null) {
            return $response;
        }

        $response = $this->guzzleClient->send($request);
        $this->cache->set($key, $response, self::MAX_TTL);
        return $response;
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

        $dataWrapper = $this->get($resource, $parameters);
        if ($dataWrapper === null) {
            return null;
        }

        $results = $dataWrapper->getData()->getResults();
        if (empty($results)) {
            return null;
        }

        return $results[0];
    }
}
