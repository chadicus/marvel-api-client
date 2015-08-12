<?php
namespace Chadicus\Marvel\Api\Entities;

use Chadicus\Marvel\Api\Client;
use Chadicus\Marvel\Api\Assets\ComicAdapter;

/**
 * Unit tests for the Comic class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Comic
 * @covers ::<protected>
 */
final class ComicTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic behavior of getId.
     *
     * @test
     *
     * @return void
     */
    public function getId()
    {
        $input = self::getTestData();
        $this->assertSame($input['id'], (new Comic($input))->getId());
    }

    /**
     * Verify basic behavior of getDigitalId.
     *
     * @test
     *
     * @return void
     */
    public function getDigitalId()
    {
        $input = self::getTestData();
        $this->assertSame($input['digitalId'], (new Comic($input))->getDigitalId());
    }

    /**
     * Verify basic behavior of getTitle.
     *
     * @test
     *
     * @return void
     */
    public function getTitle()
    {
        $input = self::getTestData();
        $this->assertSame($input['title'], (new Comic($input))->getTitle());
    }

    /**
     * Verify basic behavior of getIssueNumber.
     *
     * @test
     *
     * @return void
     */
    public function getIssueNumber()
    {
        $input = self::getTestData();
        $this->assertSame($input['issueNumber'], (new Comic($input))->getIssueNumber());
    }

    /**
     * Verify basic behavior of getVariantDescription.
     *
     * @test
     *
     * @return void
     */
    public function getVariantDescription()
    {
        $input = self::getTestData();
        $this->assertSame($input['variantDescription'], (new Comic($input))->getVariantDescription());
    }

    /**
     * Verify basic behavior of getDescription.
     *
     * @test
     *
     * @return void
     */
    public function getDescription()
    {
        $input = self::getTestData();
        $this->assertSame($input['description'], (new Comic($input))->getDescription());
    }

    /**
     * Verify basic behavior of getModified.
     *
     * @test
     *
     * @return void
     */
    public function getModified()
    {
        $input = self::getTestData();
        $this->assertSame($input['modified'], (new Comic($input))->getModified()->format('r'));
    }

    /**
     * Verify basic behavior of getIsbn.
     *
     * @test
     *
     * @return void
     */
    public function getIsbn()
    {
        $input = self::getTestData();
        $this->assertSame($input['isbn'], (new Comic($input))->getIsbn());
    }

    /**
     * Verify basic behavior of getUpc.
     *
     * @test
     *
     * @return void
     */
    public function getUpc()
    {
        $input = self::getTestData();
        $this->assertSame($input['upc'], (new Comic($input))->getUpc());
    }

    /**
     * Verify basic behavior of getDiamondCode.
     *
     * @test
     *
     * @return void
     */
    public function getDiamondCode()
    {
        $input = self::getTestData();
        $this->assertSame($input['diamondCode'], (new Comic($input))->getDiamondCode());
    }

    /**
     * Verify basic behavior of getEan.
     *
     * @test
     *
     * @return void
     */
    public function getEan()
    {
        $input = self::getTestData();
        $this->assertSame($input['ean'], (new Comic($input))->getEan());
    }

    /**
     * Verify basic behavior of getIssn.
     *
     * @test
     *
     * @return void
     */
    public function getIssn()
    {
        $input = self::getTestData();
        $this->assertSame($input['issn'], (new Comic($input))->getIssn());
    }

    /**
     * Verify basic behavior of getFormat.
     *
     * @test
     *
     * @return void
     */
    public function getFormat()
    {
        $input = self::getTestData();
        $this->assertSame($input['format'], (new Comic($input))->getFormat());
    }

    /**
     * Verify basic behavior of getPageCount.
     *
     * @test
     *
     * @return void
     */
    public function getPageCount()
    {
        $input = self::getTestData();
        $this->assertSame($input['pageCount'], (new Comic($input))->getPageCount());
    }

    /**
     * Verify basic behavior of getTextObjects.
     *
     * @test
     *
     * @return void
     */
    public function getTextObjects()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame(count($input['textObjects']), count($comic->getTextObjects()));
        foreach ($comic->getTextObjects() as $key => $textObject) {
            $this->assertSame($input['textObjects'][$key]['type'], $textObject->getType());
            $this->assertSame($input['textObjects'][$key]['language'], $textObject->getLanguage());
            $this->assertSame($input['textObjects'][$key]['text'], $textObject->getText());
        }
    }

    /**
     * Verify basic behavior of getResourceURI.
     *
     * @test
     *
     * @return void
     */
    public function getResourceURI()
    {
        $input = self::getTestData();
        $this->assertSame($input['resourceURI'], (new Comic($input))->getResourceURI());
    }

    /**
     * Verify basic behavior of getUrls.
     *
     * @test
     *
     * @return void
     */
    public function getUrls()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame(count($input['urls']), count($comic->getUrls()));
        foreach ($comic->getUrls() as $key => $url) {
            $this->assertSame($input['urls'][$key]['type'], $url->getType());
            $this->assertSame($input['urls'][$key]['url'], $url->getUrl());
        }
    }

    /**
     * Verify basic behavior of getSeries.
     *
     * @test
     *
     * @return void
     */
    public function getSeries()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame($input['series']['resourceURI'], $comic->getSeries()->getResourceURI());
        $this->assertSame($input['series']['name'], $comic->getSeries()->getName());
        $this->assertSame($input['series']['type'], $comic->getSeries()->getType());
        $this->assertSame($input['series']['role'], $comic->getSeries()->getRole());
    }

    /**
     * Verify basic behavior of getEvents.
     *
     * @test
     *
     * @return void
     */
    public function getEvents()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame($input['events']['collectionURI'], $comic->getEvents()->getCollectionURI());
        $this->assertSame($input['events']['available'], $comic->getEvents()->getAvailable());
        $this->assertSame($input['events']['returned'], $comic->getEvents()->getReturned());
        $this->assertSame(count($input['events']['items']), count($comic->getEvents()->getItems()));
        foreach ($comic->getEvents()->getItems() as $key => $item) {
            $this->assertSame($input['events']['items'][$key]['resourceURI'], $item->getResourceURI());
            $this->assertSame($input['events']['items'][$key]['name'], $item->getName());
            $this->assertSame($input['events']['items'][$key]['type'], $item->getType());
            $this->assertSame($input['events']['items'][$key]['role'], $item->getRole());
        }
    }

    /**
     * Verify basic behavior of getStories.
     *
     * @test
     *
     * @return void
     */
    public function getStories()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame($input['stories']['collectionURI'], $comic->getStories()->getCollectionURI());
        $this->assertSame($input['stories']['available'], $comic->getStories()->getAvailable());
        $this->assertSame($input['stories']['returned'], $comic->getStories()->getReturned());
        $this->assertSame(count($input['stories']['items']), count($comic->getStories()->getItems()));
        foreach ($comic->getStories()->getItems() as $key => $item) {
            $this->assertSame($input['stories']['items'][$key]['resourceURI'], $item->getResourceURI());
            $this->assertSame($input['stories']['items'][$key]['name'], $item->getName());
            $this->assertSame($input['stories']['items'][$key]['type'], $item->getType());
            $this->assertSame($input['stories']['items'][$key]['role'], $item->getRole());
        }
    }

    /**
     * Verify basic behavior of getCreators.
     *
     * @test
     *
     * @return void
     */
    public function getCreators()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame($input['creators']['collectionURI'], $comic->getCreators()->getCollectionURI());
        $this->assertSame($input['creators']['available'], $comic->getCreators()->getAvailable());
        $this->assertSame($input['creators']['returned'], $comic->getCreators()->getReturned());
        $this->assertSame(count($input['creators']['items']), count($comic->getCreators()->getItems()));
        foreach ($comic->getCreators()->getItems() as $key => $item) {
            $this->assertSame($input['creators']['items'][$key]['resourceURI'], $item->getResourceURI());
            $this->assertSame($input['creators']['items'][$key]['name'], $item->getName());
            $this->assertSame($input['creators']['items'][$key]['type'], $item->getType());
            $this->assertSame($input['creators']['items'][$key]['role'], $item->getRole());
        }
    }

    /**
     * Verify basic behavior of getCharacters.
     *
     * @test
     *
     * @return void
     */
    public function getCharacters()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame($input['characters']['collectionURI'], $comic->getCharacters()->getCollectionURI());
        $this->assertSame($input['characters']['available'], $comic->getCharacters()->getAvailable());
        $this->assertSame($input['characters']['returned'], $comic->getCharacters()->getReturned());
        $this->assertSame(count($input['characters']['items']), count($comic->getCharacters()->getItems()));
        foreach ($comic->getCharacters()->getItems() as $key => $item) {
            $this->assertSame($input['characters']['items'][$key]['resourceURI'], $item->getResourceURI());
            $this->assertSame($input['characters']['items'][$key]['name'], $item->getName());
            $this->assertSame($input['characters']['items'][$key]['type'], $item->getType());
            $this->assertSame($input['characters']['items'][$key]['role'], $item->getRole());
        }
    }

    /**
     * Verify basic behavior of getVariants.
     *
     * @test
     *
     * @return void
     */
    public function getVariants()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame(count($input['variants']), count($comic->getVariants()));
        foreach ($comic->getVariants() as $key => $variant) {
            $this->assertSame($input['variants'][$key]['resourceURI'], $variant->getResourceURI());
            $this->assertSame($input['variants'][$key]['name'], $variant->getName());
            $this->assertSame($input['variants'][$key]['type'], $variant->getType());
            $this->assertSame($input['variants'][$key]['role'], $variant->getRole());
        }
    }

    /**
     * Verify basic behavior of getCollections.
     *
     * @test
     *
     * @return void
     */
    public function getCollections()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame(count($input['collections']), count($comic->getCollections()));
        foreach ($comic->getCollections() as $key => $collection) {
            $this->assertSame($input['collections'][$key]['resourceURI'], $collection->getResourceURI());
            $this->assertSame($input['collections'][$key]['name'], $collection->getName());
            $this->assertSame($input['collections'][$key]['type'], $collection->getType());
            $this->assertSame($input['collections'][$key]['role'], $collection->getRole());
        }
    }

    /**
     * Verify basic behavior of getCollectedIssues.
     *
     * @test
     *
     * @return void
     */
    public function getCollectedIssues()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame(count($input['collectedIssues']), count($comic->getCollectedIssues()));
        foreach ($comic->getCollectedIssues() as $key => $issue) {
            $this->assertSame($input['collectedIssues'][$key]['resourceURI'], $issue->getResourceURI());
            $this->assertSame($input['collectedIssues'][$key]['name'], $issue->getName());
            $this->assertSame($input['collectedIssues'][$key]['type'], $issue->getType());
            $this->assertSame($input['collectedIssues'][$key]['role'], $issue->getRole());
        }
    }

    /**
     * Verify basic behavior of getDates.
     *
     * @test
     *
     * @return void
     */
    public function getDates()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame(count($input['dates']), count($comic->getDates()));
        foreach ($comic->getDates() as $key => $date) {
            $this->assertSame($input['dates'][$key]['date'], $date->getDate()->format('r'));
            $this->assertSame($input['dates'][$key]['type'], $date->getType());
        }
    }

    /**
     * Verify basic behavior of getPrices.
     *
     * @test
     *
     * @return void
     */
    public function getPrices()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame(count($input['prices']), count($comic->getPrices()));
        foreach ($comic->getPrices() as $key => $date) {
            $this->assertSame($input['prices'][$key]['price'], $date->getPrice());
            $this->assertSame($input['prices'][$key]['type'], $date->getType());
        }
    }

    /**
     * Verify basic behavior of getThumbnail.
     *
     * @test
     *
     * @return void
     */
    public function getThumbnail()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame($input['thumbnail']['path'], $comic->getThumbnail()->getPath());
        $this->assertSame($input['thumbnail']['extension'], $comic->getThumbnail()->getExtension());
    }

    /**
     * Verify basic behavior of getImages.
     *
     * @test
     *
     * @return void
     */
    public function getImages()
    {
        $input = self::getTestData();
        $comic = new Comic($input);
        $this->assertSame(count($input['images']), count($comic->getImages()));
        foreach ($comic->getImages() as $key => $image) {
            $this->assertSame($input['images'][$key]['path'], $image->getPath());
            $this->assertSame($input['images'][$key]['extension'], $image->getExtension());
        }
    }

    /**
     * Verify basic behavior of findAll().
     *
     * @test
     * @covers ::findAll
     *
     * @return void
     */
    public function findAll()
    {
        $client = new Client('not under test', 'not under test', new ComicAdapter());
        $comics = Comic::findAll($client, []);

        $this->assertSame(5, $comics->count());
        foreach ($comics as $key => $comic) {
            $this->assertSame($key + 1, $comic->getId());
        }
    }

    /**
     * Verify query parameters are set properly with findAll().
     *
     * @test
     * @covers ::findAll
     *
     * @return void
     */
    public function findAllParametersSetProperly()
    {
        $now = new \DateTime();
        $toDate = new \DateTime('-2 days');
        $fromDate = new \DateTime('-3 days');
        $criteria = [
            'noVariants' => true,
            'hasDigitalIssue' => false,
            'modifiedSince' => $now->format('r'),
            'series' => [2, 4, 6],
            'events' => [1, 3, 5],
            'stories' => [7, 8, 9],
            'characters' => [2, 3, 4],
            'creators' => [5, 6, 7],
            'toDate' => $toDate->format('r'),
            'fromDate' => $fromDate->format('r'),
        ];
        $adapter = new ComicAdapter();
        $client = new Client('not under test', 'not under test', $adapter);
        $comics = Comic::findAll($client, $criteria);

        $comics->next();

        $expectedParameters = [
            'noVariants' => 'true',
            'hasDigitalIssue' => 'false',
            'modifiedSince' => $now->format('c'),
            'series' => '2,4,6',
            'events' => '1,3,5',
            'stories' => '7,8,9',
            'characters' => '2,3,4',
            'creators' => '5,6,7',
            'dateRange' => "{$fromDate->format('c')},{$toDate->format('c')}",
        ];

        foreach ($expectedParameters as $key => $value) {
            $this->assertSame($value, $adapter->parameters[$key]);
        }
    }

    /**
     * Helper method to return test comic input data
     *
     * @return array
     */
    private static function getTestData()
    {
        return [
            'id' => 1,
            'digitalId' => 2,
            'title' => 'a title',
            'issueNumber' => '3',
            'variantDescription' => 'a variant description',
            'description' => 'a description',
            'modified' => 'Fri, 19 Jun 2015 15:54:05 -0400',
            'isbn' => 'an isbn',
            'upc' => 'a upc',
            'diamondCode' => 'a diamond code',
            'ean' => 'an ean',
            'issn' => 'an issn',
            'format' => 'a format',
            'pageCount' => 4,
            'textObjects' => [
                [
                    'type' => 'a text object type',
                    'language' => 'a language',
                    'text' => 'a text',
                ],
            ],
            'resourceURI' => 'a resource URI',
            'urls' => [
                [
                    'type' => 'a url type',
                    'url' => 'a url',
                ],
            ],
            'series' => [
                'resourceURI' => 'a series resource URI',
                'name' => 'a series name',
                'type' => 'a series type',
                'role' => 'a series role',
            ],
            'events' => [
                'available' => 1,
                'returned' => 1,
                'collectionURI' => 'an events collection uri',
                'items' => [
                    [
                        'resourceURI' => 'a event resource URI',
                        'name' => 'a event name',
                        'type' => 'a event type',
                        'role' => 'a event role',
                    ]
                ],
            ],
            'stories' => [
                'available' => 1,
                'returned' => 1,
                'collectionURI' => 'an stories collection uri',
                'items' => [
                    [
                        'resourceURI' => 'a story resource URI',
                        'name' => 'a story name',
                        'type' => 'a story type',
                        'role' => 'a story role',
                    ]
                ],
            ],
            'creators' => [
                'available' => 1,
                'returned' => 1,
                'collectionURI' => 'an creators collection uri',
                'items' => [
                    [
                        'resourceURI' => 'a creator resource URI',
                        'name' => 'a creator name',
                        'type' => 'a creator type',
                        'role' => 'a creator role',
                    ]
                ],
            ],
            'characters' => [
                'available' => 1,
                'returned' => 1,
                'collectionURI' => 'an characters collection uri',
                'items' => [
                    [
                        'resourceURI' => 'a character resource URI',
                        'name' => 'a character name',
                        'type' => 'a character type',
                        'role' => 'a character role',
                    ]
                ],
            ],
            'variants' => [
                [
                    'resourceURI' => 'a variant resource URI',
                    'name' => 'a variant name',
                    'type' => 'a variant type',
                    'role' => 'a variant role',
                ],
            ],
            'collections' => [
                [
                    'resourceURI' => 'a collection resource URI',
                    'name' => 'a collection name',
                    'type' => 'a collection type',
                    'role' => 'a collection role',
                ],
            ],
            'collectedIssues' => [
                [
                    'resourceURI' => 'a collected issues resource URI',
                    'name' => 'a collected issues name',
                    'type' => 'a collected issues type',
                    'role' => 'a collected issues role',
                ],
            ],
            'dates' => [
                [
                    'type' => 'a date type',
                    'date' => 'Fri, 31 Jul 2015 08:53:11 -0400',
                ],
            ],
            'prices' => [
                [
                    'type' => 'a price type',
                    'price' => 1.1,
                ],
            ],
            'thumbnail' => [
                'path' => 'a thumbnail path',
                'extension' => 'a thumbnail extension',
            ],
            'images' => [
                [
                    'path' => 'an image path',
                    'extension' => 'an image extension',
                ],
            ],
        ];
    }
}
