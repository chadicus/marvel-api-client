<?php
namespace Chadicus\Marvel\Api;

/**
 * Adapter that returns multiple items.
 */
final class CollectionAdapter implements Adapter
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
            ['id' => 0, 'name' => 'a name for item 0'],
            ['id' => 1, 'name' => 'a name for item 1'],
            ['id' => 2, 'name' => 'a name for item 2'],
            ['id' => 3, 'name' => 'a name for item 3'],
            ['id' => 4, 'name' => 'a name for item 4'],
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
