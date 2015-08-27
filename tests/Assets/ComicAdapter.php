<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Chadicus\Marvel\Api\Entities;
use Chadicus\Marvel\Api\Client;
use Chadicus\Marvel\Api\RequestInterface;
use Chadicus\Marvel\Api\Response;

/**
 * Adapter that returns multiple items.
 */
final class ComicAdapter implements AdapterInterface
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
        $allResults = self::getAllResults();

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

    /**
     * Helper method to get test data
     *
     * @return array
     */
    private static function getAllResults()
    {
        $allResults = [];
        for ($i = 1; $i <= 5; $i++) {
            $allResults[] = [
                'id' => $i,
                'digitalId' => $i + 1,
                'title' => "a title for comic {$i}",
                'issueNumber' => 3,
                'variantDescription' => "a variant description for comic {$i}",
                'description' => "a description for comic {$i}",
                'modified' => 'Fri, 19 Jun 2015 15:54:05 -0400',
                'isbn' => "an isbn for comic {$i}",
                'upc' => "a upc for comic {$i}",
                'diamondCode' => "a diamond code for comic {$i}",
                'ean' => "an ean for comic {$i}",
                'issn' => "an issn for comic {$i}",
                'format' => "a format for comic {$i}",
                'pageCount' => 4,
                'textObjects' => [
                    [
                        'type' => "a text object type for comic {$i}",
                        'language' => "a language for comic {$i}",
                        'text' => 'a text',
                    ],
                ],
                'resourceURI' => Client::BASE_URL . Entities\Comic::API_RESOURCE . "/$i",
                'urls' => [['type' => "a url type for comic {$i}", 'url' => 'a url' ]],
                'series' => [
                    'resourceURI' => "a series resource URI for comic {$i}",
                    'name' => "a series name for comic {$i}",
                    'type' => "a series type for comic {$i}",
                    'role' => "a series role for comic {$i}",
                ],
                'events' => [
                    'available' => 1,
                    'returned' => 1,
                    'collectionURI' => "an events collection uri for comic {$i}",
                    'items' => [
                        [
                            'resourceURI' => "a event resource URI for comic {$i}",
                            'name' => "a event name for comic {$i}",
                            'type' => "a event type for comic {$i}",
                            'role' => "a event role for comic {$i}",
                        ]
                    ],
                ],
                'stories' => [
                    'available' => 1,
                    'returned' => 1,
                    'collectionURI' => "an stories collection uri for comic {$i}",
                    'items' => [
                        [
                            'resourceURI' => "a story resource URI for comic {$i}",
                            'name' => "a story name for comic {$i}",
                            'type' => "a story type for comic {$i}",
                            'role' => "a story role for comic {$i}",
                        ]
                    ],
                ],
                'creators' => [
                    'available' => 1,
                    'returned' => 1,
                    'collectionURI' => "an creators collection uri for comic {$i}",
                    'items' => [
                        [
                            'resourceURI' => "a creator resource URI for comic {$i}",
                            'name' => "a creator name for comic {$i}",
                            'type' => "a creator type for comic {$i}",
                            'role' => "a creator role for comic {$i}",
                        ]
                    ],
                ],
                'characters' => [
                    'available' => 1,
                    'returned' => 1,
                    'collectionURI' => "an characters collection uri for comic {$i}",
                    'items' => [
                        [
                            'resourceURI' => "a character resource URI for comic {$i}",
                            'name' => "a character name for comic {$i}",
                            'type' => "a character type for comic {$i}",
                            'role' => "a character role for comic {$i}",
                        ]
                    ],
                ],
                'variants' => [
                    [
                        'resourceURI' => "a variant resource URI for comic {$i}",
                        'name' => "a variant name for comic {$i}",
                        'type' => "a variant type for comic {$i}",
                        'role' => "a variant role for comic {$i}",
                    ],
                ],
                'collections' => [
                    [
                        'resourceURI' => "a collection resource URI for comic {$i}",
                        'name' => "a collection name for comic {$i}",
                        'type' => "a collection type for comic {$i}",
                        'role' => "a collection role for comic {$i}",
                    ],
                ],
                'collectedIssues' => [
                    [
                        'resourceURI' => "a collected issues resource URI for comic {$i}",
                        'name' => "a collected issues name for comic {$i}",
                        'type' => "a collected issues type for comic {$i}",
                        'role' => "a collected issues role for comic {$i}",
                    ],
                ],
                'dates' => [
                    [
                        'type' => "a date type for comic {$i}",
                        'date' => 'Fri, 31 Jul 2015 08:53:11 -0400',
                    ],
                ],
                'prices' => [
                    [
                        'type' => "a price type for comic {$i}",
                        'price' => 1.1,
                    ],
                ],
                'thumbnail' => [
                    'path' => "a thumbnail path for comic {$i}",
                    'extension' => "a thumbnail extension for comic {$i}",
                ],
                'images' => [
                    [
                        'path' => "an image path for comic {$i}",
                        'extension' => "an image extension for comic {$i}",
                    ],
                ],
            ];
        }
        return $allResults;
    }
}
