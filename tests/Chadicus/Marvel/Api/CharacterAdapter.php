<?php
namespace Chadicus\Marvel\Api;

/**
 * Adapter that returns multiple items.
 */
final class CharacterAdapter implements Adapter
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
     * @param Request $request The request.
     *
     * @return Response
     */
    public function send(Request $request)
    {
        $allResults = [
            [
                'id' => 0,
                'name' => 'a name for character 0',
                'description' => 'a description for character 0',
                'modified' => '2014-01-21T18:01:51-0500',
                'resourceURI' => 'a resource uri',
                'urls' => [['type' => 'a type', 'url' => 'a url']],
                'thumbnail' => ['path' => 'a path', 'extension' => 'an extension'],
                'comics' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a comics collection uri',
                    'items' => [['resourceURI' => 'a comics resource uri', 'name' => 'a comics name']],
                ],
                'stories' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a stories collection uri',
                    'items' => [['resourceURI' => 'a stories resource uri', 'name' => 'a stories name']],
                ],
                'events' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a events collection uri',
                    'items' => [['resourceURI' => 'a events resource uri', 'name' => 'a events name']],
                ],
                'series' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a series collection uri',
                    'items' => [['resourceURI' => 'a series resource uri', 'name' => 'a series name']],
                ],
            ],
            [
                'id' => 1,
                'name' => 'a name for character 1',
                'description' => 'a description for character 1',
                'modified' => '2014-01-21T18:01:51-0500',
                'resourceURI' => 'a resource uri',
                'urls' => [['type' => 'a type', 'url' => 'a url']],
                'thumbnail' => ['path' => 'a path', 'extension' => 'an extension'],
                'comics' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a comics collection uri',
                    'items' => [['resourceURI' => 'a comics resource uri', 'name' => 'a comics name']],
                ],
                'stories' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a stories collection uri',
                    'items' => [['resourceURI' => 'a stories resource uri', 'name' => 'a stories name']],
                ],
                'events' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a events collection uri',
                    'items' => [['resourceURI' => 'a events resource uri', 'name' => 'a events name']],
                ],
                'series' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a series collection uri',
                    'items' => [['resourceURI' => 'a series resource uri', 'name' => 'a series name']],
                ],
            ],
            [
                'id' => 2,
                'name' => 'a name for character 2',
                'description' => 'a description for character 2',
                'modified' => '2014-01-21T18:01:51-0500',
                'resourceURI' => 'a resource uri',
                'urls' => [['type' => 'a type', 'url' => 'a url']],
                'thumbnail' => ['path' => 'a path', 'extension' => 'an extension'],
                'comics' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a comics collection uri',
                    'items' => [['resourceURI' => 'a comics resource uri', 'name' => 'a comics name']],
                ],
                'stories' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a stories collection uri',
                    'items' => [['resourceURI' => 'a stories resource uri', 'name' => 'a stories name']],
                ],
                'events' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a events collection uri',
                    'items' => [['resourceURI' => 'a events resource uri', 'name' => 'a events name']],
                ],
                'series' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a series collection uri',
                    'items' => [['resourceURI' => 'a series resource uri', 'name' => 'a series name']],
                ],
            ],
            [
                'id' => 3,
                'name' => 'a name for character 3',
                'description' => 'a description for character 3',
                'modified' => '2014-01-21T18:01:51-0500',
                'resourceURI' => 'a resource uri',
                'urls' => [['type' => 'a type', 'url' => 'a url']],
                'thumbnail' => ['path' => 'a path', 'extension' => 'an extension'],
                'comics' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a comics collection uri',
                    'items' => [['resourceURI' => 'a comics resource uri', 'name' => 'a comics name']],
                ],
                'stories' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a stories collection uri',
                    'items' => [['resourceURI' => 'a stories resource uri', 'name' => 'a stories name']],
                ],
                'events' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a events collection uri',
                    'items' => [['resourceURI' => 'a events resource uri', 'name' => 'a events name']],
                ],
                'series' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a series collection uri',
                    'items' => [['resourceURI' => 'a series resource uri', 'name' => 'a series name']],
                ],
            ],
            [
                'id' => 4,
                'name' => 'a name for character 4',
                'description' => 'a description for character 4',
                'modified' => '2014-01-21T18:01:51-0500',
                'resourceURI' => 'a resource uri',
                'urls' => [['type' => 'a type', 'url' => 'a url']],
                'thumbnail' => ['path' => 'a path', 'extension' => 'an extension'],
                'comics' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a comics collection uri',
                    'items' => [['resourceURI' => 'a comics resource uri', 'name' => 'a comics name']],
                ],
                'stories' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a stories collection uri',
                    'items' => [['resourceURI' => 'a stories resource uri', 'name' => 'a stories name']],
                ],
                'events' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a events collection uri',
                    'items' => [['resourceURI' => 'a events resource uri', 'name' => 'a events name']],
                ],
                'series' => [
                    'available' => 2,
                    'returned' => 1,
                    'collectionURI' => 'a series collection uri',
                    'items' => [['resourceURI' => 'a series resource uri', 'name' => 'a series name']],
                ],
            ],
        ];

        $queryString = parse_url($request->getUrl(), PHP_URL_QUERY);
        $queryParams = [];
        parse_str($queryString, $queryParams);
        $this->parameters = $queryParams;

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
