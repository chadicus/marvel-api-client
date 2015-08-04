<?php
namespace Chadicus\Marvel\Api\Entities;

use Chadicus\Marvel\Api\Client;
use Chadicus\Marvel\Api\Assets\CharacterAdapter;

/**
 * Unit tests for the Character class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Character
 * @covers ::<protected>
 */
final class CharacterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify properties are set properly.
     *
     * @test
     *
     * @return void
     */
    public function construct()
    {
        $client = new Client('not under test', 'not under test', new CharacterAdapter());

        $now = new \DateTime();

        $data = [
            'id' => 1,
            'name' => 'a name',
            'description' => 'a description',
            'modified' => $now->format('r'),
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
        ];

        $character = new Character($data);
        $this->assertSame(1, $character->getId());
        $this->assertSame('a name', $character->getName());
        $this->assertSame('a description', $character->getDescription());
        $this->assertSame($now->getTimestamp(), $character->getModified()->getTimestamp());
        $this->assertSame('a resource uri', $character->getResourceURI());
        $this->assertSame(1, count($character->getUrls()));
        $this->assertSame('a type', $character->getUrls()[0]->getType());
        $this->assertSame('a url', $character->getUrls()[0]->getUrl());
        $this->assertSame('a path', $character->getThumbnail()->getPath());
        $this->assertSame('an extension', $character->getThumbnail()->getExtension());

        $this->assertSame(2, $character->getComics()->getAvailable());
        $this->assertSame(1, $character->getComics()->getReturned());
        $this->assertSame('a comics collection uri', $character->getComics()->getCollectionURI());
        $this->assertSame(1, count($character->getComics()->getItems()));
        $this->assertSame('a comics resource uri', $character->getComics()->getItems()[0]->getResourceURI());
        $this->assertSame('a comics name', $character->getComics()->getItems()[0]->getName());

        $this->assertSame(2, $character->getStories()->getAvailable());
        $this->assertSame(1, $character->getStories()->getReturned());
        $this->assertSame(1, count($character->getStories()->getItems()));
        $this->assertSame('a stories resource uri', $character->getStories()->getItems()[0]->getResourceURI());
        $this->assertSame('a stories name', $character->getStories()->getItems()[0]->getName());

        $this->assertSame(2, $character->getEvents()->getAvailable());
        $this->assertSame(1, $character->getEvents()->getReturned());
        $this->assertSame('a events collection uri', $character->getEvents()->getCollectionURI());
        $this->assertSame(1, count($character->getEvents()->getItems()));
        $this->assertSame('a events resource uri', $character->getEvents()->getItems()[0]->getResourceURI());
        $this->assertSame('a events name', $character->getEvents()->getItems()[0]->getName());

        $this->assertSame(2, $character->getSeries()->getAvailable());
        $this->assertSame(1, $character->getSeries()->getReturned());
        $this->assertSame('a series collection uri', $character->getSeries()->getCollectionURI());
        $this->assertSame(1, count($character->getSeries()->getItems()));
        $this->assertSame('a series resource uri', $character->getSeries()->getItems()[0]->getResourceURI());
        $this->assertSame('a series name', $character->getSeries()->getItems()[0]->getName());
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
        $client = new Client('not under test', 'not under test', new CharacterAdapter());
        $characters = Character::findAll($client);

        $this->assertSame(5, $characters->count());
        foreach ($characters as $key => $character) {
            $this->assertSame($key, $character->getId());
            $this->assertSame("a name for character {$key}", $character->getName());
            $this->assertSame("a description for character {$key}", $character->getDescription());
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
        $criteria = [
            'name' => 'a name',
            'modifiedSince' => $now->format('r'),
            'comics' => [1,2,3],
            'series' => [2,4,6],
            'events' => [1,3,5],
            'stories' => [7,8,9],
            'orderBy' => 'name',
        ];
        $adapter = new CharacterAdapter();
        $client = new Client('not under test', 'not under test', $adapter);
        $characters = Character::findAll($client, $criteria);

        $characters->next();

        $expectedParameters = [
            'name' => 'a name',
            'modifiedSince' => $now->format('c'),
            'comics' => '1,2,3',
            'series' => '2,4,6',
            'events' => '1,3,5',
            'stories' => '7,8,9',
            'orderBy' => 'name',
        ];

        foreach ($expectedParameters as $key => $value) {
            $this->assertSame($value, $adapter->parameters[$key]);
        }
    }
}
