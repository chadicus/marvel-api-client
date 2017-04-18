<?php

namespace Chadicus\Marvel\Api;

use GuzzleHttp;
use Psr\SimpleCache\CacheInterface;

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
     * The public API key issued by Marvel.
     *
     * @var string
     */
    private $publicApiKey;

    /**
     * The private API key issued by Marvel.
     *
     * @var string
     */
    private $privateApiKey;

    /**
     * Guzzle HTTP Client implementation.
     *
     * @var GuzzleHttp\ClientInterface
     */
    private $guzzleClient;

    /**
     * Cache implementation.
     *
     * @var CacheInterface
     */
    private $cache;

    /**
     * Default Guzzle request options.
     *
     * @var array
     */
    private static $guzzleRequestOptions = [
        'http_errors' => false,
        'headers' => [
            'Accept' =>  'application/json',
            'Accept-Encoding' => 'gzip,deflate',
        ],
    ];

    /**
     * The Marvel API URL.
     *
     * @const string
     */
    const BASE_URL = 'http://gateway.marvel.com/v1/public/';

    /**
     * Construct a new Client.
     *
     * @param string                     $privateApiKey The private API key issued by Marvel.
     * @param string                     $publicApiKey  The public API key issued by Marvel.
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
     * @return null|DataWrapper
     */
    final public function search(string $resource, array $filters = [])
    {
        return $this->send($resource, null, $filters);
    }

    /**
     * Execute a GET request against the Marvel API for a single resource.
     *
     * @param string  $resource The API resource to search for.
     * @param integer $id       The id of the API resource.
     *
     * @return null|DataWrapper
     */
    final public function get(string $resource, int $id)
    {
        return $this->send($resource, $id);
    }

    /**
     * Send the given API URL request.
     *
     * @param string  $resource The API resource to search for.
     * @param integer $id       The id of a specific API resource.
     * @param array   $query    Array of search criteria to use in request.
     *
     * @return null|DataWrapperInterface
     */
    final private function send(string $resource, int $id = null, array $query = [])
    {
        $query['apikey'] = $this->publicApiKey;
        $query['ts'] = time();
        $query['hash'] = md5("{$query['ts']}{$this->privateApiKey}{$this->publicApiKey}");
        $url = self::BASE_URL . urlencode($resource) . ($id !== null ? "/{$id}" : '') . '?' . http_build_query($query);

        $response = $this->cache->get($url);
        if ($response === null) {
            $response = $this->guzzleClient->request('GET', $url, self::$guzzleRequestOptions);
        }

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $this->cache->set($url, $response, self::MAX_TTL);

        return DataWrapper::fromJson((string)$response->getBody());
    }

    /**
     * Allow calls such as $client->characters();
     *
     * @param string $name      The name of the API resource.
     * @param array  $arguments The parameters to pass to get() or search().
     *
     * @return Collection|EntityInterface|null
     */
    final public function __call(string $name, array $arguments)
    {
        $resource = strtolower($name);
        $idOrFilters = array_shift($arguments) ?: [];

        if (is_array($idOrFilters)) {
            return new Collection($this, $resource, $idOrFilters);
        }

        $dataWrapper = $this->send($resource, $idOrFilters);
        if ($dataWrapper === null) {
            return null;
        }

        $results = $dataWrapper->getData()->getResults();
        return array_shift($results);
    }
}
