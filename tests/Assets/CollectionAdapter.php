<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Chadicus\Marvel\Api\Client;
use Chadicus\Marvel\Api\Request;
use Chadicus\Marvel\Api\Response;

/**
 * Adapter that returns multiple items.
 */
final class CollectionAdapter implements AdapterInterface
{
    /**
     * Simulate sending a request to the API.
     *
     * @param Request $request The request.
     *
     * @return Response
     */
    public function send(Request $request)
    {
        $allResults = [
            ['id' => 0, 'title' => 'a title for comic 0', 'resourceURI' => Client::BASE_URL . 'comics/0'],
            ['id' => 1, 'title' => 'a title for comic 1', 'resourceURI' => Client::BASE_URL . 'comics/1'],
            ['id' => 2, 'title' => 'a title for comic 2', 'resourceURI' => Client::BASE_URL . 'comics/2'],
            ['id' => 3, 'title' => 'a title for comic 3', 'resourceURI' => Client::BASE_URL . 'comics/3'],
            ['id' => 4, 'title' => 'a title for comic 4', 'resourceURI' => Client::BASE_URL . 'comics/4'],
        ];

        $queryString = parse_url($request->getUrl(), PHP_URL_QUERY);
        $queryParams = [];
        parse_str($queryString, $queryParams);

        $offset = (int)$queryParams['offset'];
        $limit = (int)$queryParams['limit'];
        $results = array_slice($allResults, $offset, $limit);
        $count = count($results);
        return new Response(
            200,
            ['Content-type' => 'application/json', 'etag' => 'an etag'],
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

    }
}
