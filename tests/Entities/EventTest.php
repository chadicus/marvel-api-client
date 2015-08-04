<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * @coversDefaultClass \Chadicus\Marvel\Api\Entities\Event
 * @covers ::<protected>
 *
 */
final class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verifies basic behaviour of the Event class
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
            'modified' => 'Fri, 31 Jul 2015 19:44:03 -0400',
            'start' => 'Fri, 31 Jul 2015 19:44:03 -0400',
            'end' => 'Fri, 31 Jul 2015 19:44:03 -0400',
            'thumbnail' => ['path' => 'an image path', 'extension' => 'an image extension'],
            'comics' => ['available' => 2, 'returned' => 1, 'collectionURI' => 'a collection URI', 'items' => []],
            'stories' => ['available' => 4, 'returned' => 3, 'collectionURI' => 'a collection URI', 'items' => []],
            'series' => ['available' => 6, 'returned' => 5, 'collectionURI' => 'a collection URI', 'items' => []],
            'characters' => ['available' => 9, 'returned' => 8, 'collectionURI' => 'a collection URI', 'items' => []],
            'creators' => ['available' => 2, 'returned' => 1, 'collectionURI' => 'a collection URI', 'items' => []],
            'next' => ['resourceURI' => 'a resource URI', 'name' => 'a name', 'type' => 'a type', 'role' => 'a role'],
            'previous' => [
                'resourceURI' => 'a resource URI',
                'name' => 'a name',
                'type' => 'a type',
                'role' => 'a role',
            ],
        ];

        $event = new Event($data);
        $this->assertSame($data['id'], $event->id);
        $this->assertSame($data['title'], $event->title);
        $this->assertSame($data['description'], $event->description);
        $this->assertSame($data['resourceURI'], $event->resourceURI);
        $this->assertSame(count($data['urls']), count($event->urls));
        $this->assertSame($data['urls'][0]['type'], $event->urls[0]->type);
        $this->assertSame($data['urls'][0]['url'], $event->urls[0]->url);
        $this->assertSame($data['modified'], $event->modified->format('r'));
        $this->assertSame($data['start'], $event->start->format('r'));
        $this->assertSame($data['end'], $event->end->format('r'));
        $this->assertSame($data['thumbnail']['path'], $event->thumbnail->path);
        $this->assertSame($data['thumbnail']['extension'], $event->thumbnail->extension);
        $this->assertSame($data['comics']['available'], $event->comics->available);
        $this->assertSame($data['comics']['returned'], $event->comics->returned);
        $this->assertSame($data['comics']['collectionURI'], $event->comics->collectionURI);
        $this->assertSame($data['stories']['available'], $event->stories->available);
        $this->assertSame($data['stories']['returned'], $event->stories->returned);
        $this->assertSame($data['stories']['collectionURI'], $event->stories->collectionURI);
        $this->assertSame($data['series']['available'], $event->series->available);
        $this->assertSame($data['series']['returned'], $event->series->returned);
        $this->assertSame($data['series']['collectionURI'], $event->series->collectionURI);
        $this->assertSame($data['characters']['available'], $event->characters->available);
        $this->assertSame($data['characters']['returned'], $event->characters->returned);
        $this->assertSame($data['characters']['collectionURI'], $event->characters->collectionURI);
        $this->assertSame($data['creators']['available'], $event->creators->available);
        $this->assertSame($data['creators']['returned'], $event->creators->returned);
        $this->assertSame($data['creators']['collectionURI'], $event->creators->collectionURI);
        $this->assertSame($data['next']['resourceURI'], $event->next->resourceURI);
        $this->assertSame($data['next']['name'], $event->next->name);
        $this->assertSame($data['next']['type'], $event->next->type);
        $this->assertSame($data['next']['role'], $event->next->role);
        $this->assertSame($data['previous']['resourceURI'], $event->previous->resourceURI);
        $this->assertSame($data['previous']['name'], $event->previous->name);
        $this->assertSame($data['previous']['type'], $event->previous->type);
        $this->assertSame($data['previous']['role'], $event->previous->role);
    }
}
