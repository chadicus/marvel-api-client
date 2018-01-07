<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Chadicus\Marvel\Api\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * Mock handler for guzzle clients.
 */
final class CharacterHandler
{
    /**
     * The parameters sent with the last request.
     *
     * @var array
     */
    public $parameters = [];

    /**
     * Simulate sending a request to the API.
     *
     * @param RequestInterface $request The request.
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request)
    {
        $allResults = include __DIR__ . '/_files/characters.php';
        $queryString = $request->getUri()->getQuery();
        $queryParams = [];
        parse_str($queryString, $queryParams);
        $this->parameters = $queryParams;

        $offset = (int)$queryParams['offset'];
        $limit = (int)$queryParams['limit'];
        $results = array_slice($allResults, $offset, $limit);
        $count = count($results);

        $body = json_encode(
            [
                'code' => 200,
                'status' => 'ok',
                'etag' => 'an etag',
                'data' => [
                    'offset' => $offset,
                    'limit' => $limit,
                    'total' => 5,
                    'count' => $count,
                    'results' => $results,
                ],
            ]
        );

        return new Response(200, ['Content-type' => 'application/json', 'etag' => 'an etag'], $body);
    }
}
