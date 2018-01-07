<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Creator
 * @covers ::<protected>
 *
 */
final class CreatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verifies basic behaviour of the Creator class
     *
     * @test
     *
     * @return void
     */
    public function basicUsage()
    {
        $data = [
            'id' => 0,
            'firstName' => 'a firstName',
            'middleName' => 'a middleName',
            'lastName' => 'a lastName',
            'suffix' => 'a suffix',
            'fullName' => 'a fullName',
            'modified' => 'Fri, 31 Jul 2015 19:45:00 -0400',
            'resourceURI' => 'a resourceURI',
            'urls' => [['type' => 'a type', 'url' => 'a url']],
            'thumbnail' => ['path' => 'an image path', 'extension' => 'an image extension'],
            'series' => ['available' => 2, 'returned' => 1, 'collectionURI' => 'a collection URI', 'items' => []],
            'stories' => ['available' => 5, 'returned' => 4, 'collectionURI' => 'a collection URI', 'items' => []],
            'comics' => ['available' => 9, 'returned' => 8, 'collectionURI' => 'a collection URI', 'items' => []],
            'events' => ['available' => 5, 'returned' => 4, 'collectionURI' => 'a collection URI', 'items' => []],
        ];

        $creator = new Creator($data);
        $this->assertSame($data['id'], $creator->id);
        $this->assertSame($data['firstName'], $creator->firstName);
        $this->assertSame($data['middleName'], $creator->middleName);
        $this->assertSame($data['lastName'], $creator->lastName);
        $this->assertSame($data['suffix'], $creator->suffix);
        $this->assertSame($data['fullName'], $creator->fullName);
        $this->assertSame($data['modified'], $creator->modified->format('r'));
        $this->assertSame($data['resourceURI'], $creator->resourceURI);
        $this->assertSame(count($data['urls']), count($creator->urls));
        $this->assertSame($data['urls'][0]['type'], $creator->urls[0]->type);
        $this->assertSame($data['urls'][0]['url'], $creator->urls[0]->url);
        $this->assertSame($data['thumbnail']['path'], $creator->thumbnail->path);
        $this->assertSame($data['thumbnail']['extension'], $creator->thumbnail->extension);
        $this->assertSame($data['series']['available'], $creator->series->available);
        $this->assertSame($data['series']['returned'], $creator->series->returned);
        $this->assertSame($data['series']['collectionURI'], $creator->series->collectionURI);
        $this->assertSame($data['stories']['available'], $creator->stories->available);
        $this->assertSame($data['stories']['returned'], $creator->stories->returned);
        $this->assertSame($data['stories']['collectionURI'], $creator->stories->collectionURI);
        $this->assertSame($data['comics']['available'], $creator->comics->available);
        $this->assertSame($data['comics']['returned'], $creator->comics->returned);
        $this->assertSame($data['comics']['collectionURI'], $creator->comics->collectionURI);
        $this->assertSame($data['events']['available'], $creator->events->available);
        $this->assertSame($data['events']['returned'], $creator->events->returned);
        $this->assertSame($data['events']['collectionURI'], $creator->events->collectionURI);
    }
}
