<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Chadicus\Marvel\Api\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * Mock Handler for guzzle client.
 */
final class CollectionHandler
{
    /**
     * Simulate sending a request to the API.
     *
     * @param RequestInterface $request The request.
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request)
    {
        $allResults = [
            ['id' => 0, 'title' => 'a title for comic 0', 'resourceURI' => Client::BASE_URL . 'comics/0'],
            ['id' => 1, 'title' => 'a title for comic 1', 'resourceURI' => Client::BASE_URL . 'comics/1'],
            ['id' => 2, 'title' => 'a title for comic 2', 'resourceURI' => Client::BASE_URL . 'comics/2'],
            ['id' => 3, 'title' => 'a title for comic 3', 'resourceURI' => Client::BASE_URL . 'comics/3'],
            ['id' => 4, 'title' => 'a title for comic 4', 'resourceURI' => Client::BASE_URL . 'comics/4'],
        ];

        $queryString = parse_url($request->getUri(), PHP_URL_QUERY);
        $queryParams = [];
        parse_str($queryString, $queryParams);

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
