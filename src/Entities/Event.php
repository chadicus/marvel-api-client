<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * Represents a Marvel API Event Entity
 *
 * @property-read integer $id The unique ID of the event resource.
 * @property-read string $title The title of the event.
 * @property-read string $description A description of the event.
 * @property-read string $resourceURI The canonical URL identifier for this resource.
 * @property-read Url[] $urls A set of public web site URLs for the event.
 * @property-read DateTime $modified The date the resource was most recently modified.
 * @property-read DateTime $start The date of publication of the first issue in this event.
 * @property-read DateTime $end The date of publication of the last issue in this event.
 * @property-read Image $thumbnail The representative image for this event.
 * @property-read ResourceList $comics A resource list containing the comics in this event.
 * @property-read ResourceList $stories A resource list containing the stories in this event.
 * @property-read ResourceList $series A resource list containing the series in this event.
 * @property-read ResourceList $characters A resource list containing the characters which appear in this event.
 * @property-read ResourceList $creators A resource list containing creators whose work appears in this event.
 * @property-read Summary $next A summary representation of the event which follows this event.
 * @property-read Summary $previous A summary representation of the event which preceded this event.
 */
class Event extends AbstractEntity
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
            'urls' => ['default' => [], ['_urls']],
            'modified' => [['date']],
            'start' => [['date']],
            'end' => [['date']],
            'thumbnail' => ['default' => new Image(), ['image']],
            'comics' => ['default' => new ResourceList(), ['resource-list']],
            'stories' => ['default' => new ResourceList(), ['resource-list']],
            'series' => ['default' => new ResourceList(), ['resource-list']],
            'characters' => ['default' => new ResourceList(), ['resource-list']],
            'creators' => ['default' => new ResourceList(), ['resource-list']],
            'next' => ['default' => new Summary(), ['summary']],
            'previous' => ['default' => new Summary(), ['summary']],
        ];
    }
}
