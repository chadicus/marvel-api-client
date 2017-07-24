<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Chadicus\Marvel\Api\Entities;
use Chadicus\Marvel\Api\Client;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Response;

/**
 * Mock handler for guzzle clients.
 */
final class ComicHandler
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
        $allResults = include __DIR__ .  '/_files/comics.php';
        $total = count($allResults);

        $queryString = parse_url((string)$request->getUri(), PHP_URL_QUERY);
        $queryParams = [];
        parse_str($queryString, $queryParams);

        $path = $request->getUri()->getPath();
        if (substr($path, -6) !== 'comics') {
            $parts = explode('/', (string)$request->getUri());
            $id = (int)array_pop($parts);
            $queryParams['offset'] = $id - 1;
            $queryParams['limit'] = 1;
            $total = 1;
        }

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
                    'total' => $total,
                    'count' => $count,
                    'results' => $results,
                ],
            ]
        );

        return new Response(200, ['Content-type' => 'application/json', 'etag' => 'an etag'], $body);
    }
}
