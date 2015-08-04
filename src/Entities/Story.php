<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * Represents a Marvel API Story Entity
 *
 * @property-read integer $id The unique ID of the story resource.
 * @property-read string $title The story title.
 * @property-read string $description A short description of the story.
 * @property-read string $resourceURI The canonical URL identifier for this resource.
 * @property-read string $type The story type e.g. interior story, cover, text story.
 * @property-read DateTime $modified The date the resource was most recently modified.
 * @property-read Image $thumbnail The representative image for this story.
 * @property-read ResourceList $comics A resource list containing comics in which this story takes place.
 * @property-read ResourceList $series A resource list containing series in which this story appears.
 * @property-read ResourceList $events A resource list of the events in which this story appears.
 * @property-read ResourceList $characters A resource list of characters which appear in this story.
 * @property-read ResourceList $creators A resource list of creators who worked on this story.
 * @property-read Summary $originalissue A summary representation of the issue in which this story was originally
 *                                       published.
 */
class Story extends AbstractEntity
{
    /**
     * @see AbstractEntity::getFilters()
     *
     * @return array
     */
    final protected function getFilters()
    {
        return [
            'id' => [['int', true]],
            'title' => [['string', true, 0]],
            'description' => [['string', true, 0]],
            'resourceURI' => [['string', true, 0]],
            'type' => [['string', true, 0]],
            'modified' => [['date']],
            'thumbnail' => ['default' => new Image(), ['image']],
            'comics' => ['default' => new ResourceList(), ['resource-list']],
            'series' => ['default' => new ResourceList(), ['resource-list']],
            'events' => ['default' => new ResourceList(), ['resource-list']],
            'characters' => ['default' => new ResourceList(), ['resource-list']],
            'creators' => ['default' => new ResourceList(), ['resource-list']],
            'originalissue' => ['default' => new Summary(), ['summary']],
        ];
    }
}
