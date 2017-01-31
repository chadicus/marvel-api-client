<?php

namespace Chadicus\Marvel\Api\Cache;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Collection;
use MongoDB\Model\BSONArray;

/**
 * Concrete implementation of Cache using an array.
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
     * @param integer    $defaultTimeToLive The default time to live in seconds.
     */
    public function __construct(Collection $collection, int $defaultTimeToLive = CacheInterface::MAX_TTL)
    {
        $this->setDefaultTTL($defaultTimeToLive);
        $this->collection = $collection;
        $this->collection->createIndex(['expires' => 1], ['expireAfterSeconds' => 0, 'background' => true]);
    }

    /**
     * Store the api $response as the cached result of the api $request.
     *
     * @param RequestInterface  $request    The request for which the response will be cached.
     * @param ResponseInterface $response   The reponse to cache.
     * @param integer           $timeToLive The time in seconds that the cache should live.
     *
     * @return void
     *
     * @throws \InvalidArgumentException Throw if $timeToLive is not an integer between 0 and 86400.
     */
    public function set(RequestInterface $request, ResponseInterface $response, int $timeToLive = null)
    {
        $timeToLive = self::ensureTTL($timeToLive ?: $this->getDefaultTTL());

        $id = (string)$request->getUri();
        $cache = [
            'httpCode' => $response->getStatusCode(),
            'body' => (string)$response->getBody(),
            'headers' => $response->getHeaders(),
            'expires' => new UTCDateTime(time() + $timeToLive),
        ];

        $this->collection->updateOne(['_id' => $id], ['$set' => $cache], ['upsert' => true]);
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
        $cached = $this->collection->findOne(['_id' => (string)$request->getUri()]);
        if ($cached === null) {
            return null;
        }

        $headers = $cached['headers'];
        if ($headers instanceof BSONArray) {
            $headers = $headers->getArrayCopy();
        }

        $body = $cached['body'];
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, $body);
        rewind($stream);

        return new Response(new Stream($stream), $cached['httpCode'], $headers);
    }
}
