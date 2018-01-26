<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Series
 * @covers ::<protected>
 *
 */
final class SeriesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verifies basic behaviour of the Series class
     *
     * @test
     *
     * @return void
     */
    public function basicUsage()
    {
        $data = [
            'id' => 5,
            'title' => 'a title',
            'description' => 'a description',
            'resourceURI' => 'a resourceURI',
            'urls' => [['type' => 'a type', 'url' => 'a url']],
            'startYear' => 4,
            'endYear' => 1,
            'rating' => 'a rating',
            'modified' => 'Fri, 31 Jul 2015 19:42:19 -0400',
            'thumbnail' => ['path' => 'an image path', 'extension' => 'an image extension'],
            'comics' => ['available' => 3, 'returned' => 2, 'collectionURI' => 'a collection URI', 'items' => []],
            'stories' => ['available' => 5, 'returned' => 4, 'collectionURI' => 'a collection URI', 'items' => []],
            'events' => ['available' => 3, 'returned' => 2, 'collectionURI' => 'a collection URI', 'items' => []],
            'characters' => ['available' => 8, 'returned' => 7, 'collectionURI' => 'a collection URI', 'items' => []],
            'creators' => ['available' => 2, 'returned' => 1, 'collectionURI' => 'a collection URI', 'items' => []],
            'next' => ['resourceURI' => 'a resource URI', 'name' => 'a name', 'type' => 'a type', 'role' => 'a role'],
            'previous' => [
                'resourceURI' => 'a resource URI',
                'name' => 'a name',
                'type' => 'a type',
                'role' => 'a role',
            ],
        ];

        $series = new Series($data);
        $this->assertSame($data['id'], $series->id);
        $this->assertSame($data['title'], $series->title);
        $this->assertSame($data['description'], $series->description);
        $this->assertSame($data['resourceURI'], $series->resourceURI);
        $this->assertSame(count($data['urls']), count($series->urls));
        $this->assertSame($data['urls'][0]['type'], $series->urls[0]->type);
        $this->assertSame($data['urls'][0]['url'], $series->urls[0]->url);
        $this->assertSame($data['startYear'], $series->startYear);
        $this->assertSame($data['endYear'], $series->endYear);
        $this->assertSame($data['rating'], $series->rating);
        $this->assertSame($data['modified'], $series->modified->format('r'));
        $this->assertSame($data['thumbnail']['path'], $series->thumbnail->path);
        $this->assertSame($data['thumbnail']['extension'], $series->thumbnail->extension);
        $this->assertSame($data['comics']['available'], $series->comics->available);
        $this->assertSame($data['comics']['returned'], $series->comics->returned);
        $this->assertSame($data['comics']['collectionURI'], $series->comics->collectionURI);
        $this->assertSame($data['stories']['available'], $series->stories->available);
        $this->assertSame($data['stories']['returned'], $series->stories->returned);
        $this->assertSame($data['stories']['collectionURI'], $series->stories->collectionURI);
        $this->assertSame($data['events']['available'], $series->events->available);
        $this->assertSame($data['events']['returned'], $series->events->returned);
        $this->assertSame($data['events']['collectionURI'], $series->events->collectionURI);
        $this->assertSame($data['characters']['available'], $series->characters->available);
        $this->assertSame($data['characters']['returned'], $series->characters->returned);
        $this->assertSame($data['characters']['collectionURI'], $series->characters->collectionURI);
        $this->assertSame($data['creators']['available'], $series->creators->available);
        $this->assertSame($data['creators']['returned'], $series->creators->returned);
        $this->assertSame($data['creators']['collectionURI'], $series->creators->collectionURI);
        $this->assertSame($data['next']['resourceURI'], $series->next->resourceURI);
        $this->assertSame($data['next']['name'], $series->next->name);
        $this->assertSame($data['next']['type'], $series->next->type);
        $this->assertSame($data['next']['role'], $series->next->role);
        $this->assertSame($data['previous']['resourceURI'], $series->previous->resourceURI);
        $this->assertSame($data['previous']['name'], $series->previous->name);
        $this->assertSame($data['previous']['type'], $series->previous->type);
        $this->assertSame($data['previous']['role'], $series->previous->role);
    }

    /**
     * @test
     *
     * @return void
     */
    public function seriesWithNullPreviousValue()
    {
        $data = [
            'id' => 5,
            'title' => 'a title',
            'description' => 'a description',
            'resourceURI' => 'a resourceURI',
            'urls' => [['type' => 'a type', 'url' => 'a url']],
            'startYear' => 4,
            'endYear' => 1,
            'rating' => 'a rating',
            'modified' => 'Fri, 31 Jul 2015 19:42:19 -0400',
            'thumbnail' => ['path' => 'an image path', 'extension' => 'an image extension'],
            'comics' => ['available' => 3, 'returned' => 2, 'collectionURI' => 'a collection URI', 'items' => []],
            'stories' => ['available' => 5, 'returned' => 4, 'collectionURI' => 'a collection URI', 'items' => []],
            'events' => ['available' => 3, 'returned' => 2, 'collectionURI' => 'a collection URI', 'items' => []],
            'characters' => ['available' => 8, 'returned' => 7, 'collectionURI' => 'a collection URI', 'items' => []],
            'creators' => ['available' => 2, 'returned' => 1, 'collectionURI' => 'a collection URI', 'items' => []],
            'next' => ['resourceURI' => 'a resource URI', 'name' => 'a name', 'type' => 'a type', 'role' => 'a role'],
            'previous' => null,
        ];

        $series = new Series($data);
        $this->assertSame($data['id'], $series->id);
        $this->assertSame($data['title'], $series->title);
        $this->assertSame($data['description'], $series->description);
        $this->assertSame($data['resourceURI'], $series->resourceURI);
        $this->assertSame(count($data['urls']), count($series->urls));
        $this->assertSame($data['urls'][0]['type'], $series->urls[0]->type);
        $this->assertSame($data['urls'][0]['url'], $series->urls[0]->url);
        $this->assertSame($data['startYear'], $series->startYear);
        $this->assertSame($data['endYear'], $series->endYear);
        $this->assertSame($data['rating'], $series->rating);
        $this->assertSame($data['modified'], $series->modified->format('r'));
        $this->assertSame($data['thumbnail']['path'], $series->thumbnail->path);
        $this->assertSame($data['thumbnail']['extension'], $series->thumbnail->extension);
        $this->assertSame($data['comics']['available'], $series->comics->available);
        $this->assertSame($data['comics']['returned'], $series->comics->returned);
        $this->assertSame($data['comics']['collectionURI'], $series->comics->collectionURI);
        $this->assertSame($data['stories']['available'], $series->stories->available);
        $this->assertSame($data['stories']['returned'], $series->stories->returned);
        $this->assertSame($data['stories']['collectionURI'], $series->stories->collectionURI);
        $this->assertSame($data['events']['available'], $series->events->available);
        $this->assertSame($data['events']['returned'], $series->events->returned);
        $this->assertSame($data['events']['collectionURI'], $series->events->collectionURI);
        $this->assertSame($data['characters']['available'], $series->characters->available);
        $this->assertSame($data['characters']['returned'], $series->characters->returned);
        $this->assertSame($data['characters']['collectionURI'], $series->characters->collectionURI);
        $this->assertSame($data['creators']['available'], $series->creators->available);
        $this->assertSame($data['creators']['returned'], $series->creators->returned);
        $this->assertSame($data['creators']['collectionURI'], $series->creators->collectionURI);
        $this->assertSame($data['next']['resourceURI'], $series->next->resourceURI);
        $this->assertSame($data['next']['name'], $series->next->name);
        $this->assertSame($data['next']['type'], $series->next->type);
        $this->assertSame($data['next']['role'], $series->next->role);
        $this->assertNull($series->previous->resourceURI);
        $this->assertNull($series->previous->name);
        $this->assertNull($series->previous->type);
        $this->assertNull($series->previous->role);
    }
}
