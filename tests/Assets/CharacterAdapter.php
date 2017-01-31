<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Chadicus\Marvel\Api\Client;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

/**
 * Adapter that returns multiple items.
 */
final class CharacterAdapter implements AdapterInterface
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
    public function send(RequestInterface $request)
    {
        $allResults = [
            [
                'id' => 0,
                'name' => 'a name for character 0',
                'description' => 'a description for character 0',
                'modified' => '2014-01-21T18:01:51-0500',
                'resourceURI' => Client::BASE_URL . 'characters/0',
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
                'resourceURI' => Client::BASE_URL . 'characters/1',
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
                'resourceURI' => Client::BASE_URL . 'characters/2',
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
                'resourceURI' => Client::BASE_URL . 'characters/3',
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
                'resourceURI' => Client::BASE_URL . 'characters/4',
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

        $queryString = $request->getUri()->getQuery();
        $queryParams = [];
        parse_str($queryString, $queryParams);
        $this->parameters = $queryParams;

        $offset = (int)$queryParams['offset'];
        $limit = (int)$queryParams['limit'];
        $results = array_slice($allResults, $offset, $limit);
        $count = count($results);

        $stream = fopen('php://temp', 'r+');
        fwrite(
            $stream,
            json_encode(
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
            )
        );

        return new Response(
            new Stream($stream),
            200,
            ['Content-type' => 'application/json', 'etag' => 'an etag']
        );
    }
}
