<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Story
 * @covers ::<protected>
 *
 */
final class StoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verifies basic behaviour of the Story class
     *
     * @test
     *
     * @return void
     */
    public function basicUsage()
    {
        $data = [
            'id' => 9,
            'title' => 'a title',
            'description' => 'a description',
            'resourceURI' => 'a resourceURI',
            'type' => 'a type',
            'modified' => 'Fri, 31 Jul 2015 19:34:15 -0400',
            'thumbnail' => ['path' => 'an image path', 'extension' => 'an image extension'],
            'comics' => ['available' => 5, 'returned' => 4, 'collectionURI' => 'a collection URI', 'items' => []],
            'series' => ['available' => 4, 'returned' => 3, 'collectionURI' => 'a collection URI', 'items' => []],
            'events' => ['available' => 6, 'returned' => 5, 'collectionURI' => 'a collection URI', 'items' => []],
            'characters' => ['available' => 9, 'returned' => 8, 'collectionURI' => 'a collection URI', 'items' => []],
            'creators' => ['available' => 7, 'returned' => 6, 'collectionURI' => 'a collection URI', 'items' => []],
            'originalissue' => [
                'resourceURI' => 'a resource URI',
                'name' => 'a name',
                'type' => 'a type',
                'role' => 'a role',
            ],
        ];

        $story = new Story($data);
        $this->assertSame($data['id'], $story->id);
        $this->assertSame($data['title'], $story->title);
        $this->assertSame($data['description'], $story->description);
        $this->assertSame($data['resourceURI'], $story->resourceURI);
        $this->assertSame($data['type'], $story->type);
        $this->assertSame($data['modified'], $story->modified->format('r'));
        $this->assertSame($data['thumbnail']['path'], $story->thumbnail->path);
        $this->assertSame($data['thumbnail']['extension'], $story->thumbnail->extension);
        $this->assertSame($data['comics']['available'], $story->comics->available);
        $this->assertSame($data['comics']['returned'], $story->comics->returned);
        $this->assertSame($data['comics']['collectionURI'], $story->comics->collectionURI);
        $this->assertSame($data['series']['available'], $story->series->available);
        $this->assertSame($data['series']['returned'], $story->series->returned);
        $this->assertSame($data['series']['collectionURI'], $story->series->collectionURI);
        $this->assertSame($data['events']['available'], $story->events->available);
        $this->assertSame($data['events']['returned'], $story->events->returned);
        $this->assertSame($data['events']['collectionURI'], $story->events->collectionURI);
        $this->assertSame($data['characters']['available'], $story->characters->available);
        $this->assertSame($data['characters']['returned'], $story->characters->returned);
        $this->assertSame($data['characters']['collectionURI'], $story->characters->collectionURI);
        $this->assertSame($data['creators']['available'], $story->creators->available);
        $this->assertSame($data['creators']['returned'], $story->creators->returned);
        $this->assertSame($data['creators']['collectionURI'], $story->creators->collectionURI);
        $this->assertSame($data['originalissue']['resourceURI'], $story->originalissue->resourceURI);
        $this->assertSame($data['originalissue']['name'], $story->originalissue->name);
        $this->assertSame($data['originalissue']['type'], $story->originalissue->type);
        $this->assertSame($data['originalissue']['role'], $story->originalissue->role);
    }
}
