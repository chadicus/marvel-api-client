<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * Represents a Marvel API Series Entity
 *
 * @property-read integer $id The unique ID of the series resource.
 * @property-read string $title The canonical title of the series.
 * @property-read string $description A description of the series.
 * @property-read string $resourceURI The canonical URL identifier for this resource.
 * @property-read Url[] $urls A set of public web site URLs for the resource.
 * @property-read integer $startYear The first year of publication for the series.
 * @property-read integer $endYear The last year of publication for the series (conventionally, 2099 for ongoing
 *                                 series).
 * @property-read string $rating The age-appropriateness rating for the series.
 * @property-read DateTime $modified The date the resource was most recently modified.
 * @property-read Image $thumbnail The representative image for this series.
 * @property-read ResourceList $comics A resource list containing comics in this series.
 * @property-read ResourceList $stories A resource list containing stories which occur in comics in this series.
 * @property-read ResourceList $events A resource list containing events which take place in comics in this series.
 * @property-read ResourceList $characters A resource list containing characters which appear in comics in this series.
 * @property-read ResourceList $creators A resource list of creators whose work appears in comics in this series.
 * @property-read Summary $next A summary representation of the series which follows this series.
 * @property-read Summary $previous A summary representation of the series which preceded this series.
 */
class Series extends AbstractEntity
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
            'startYear' => [['int', true]],
            'endYear' => [['int', true]],
            'rating' => [['string', true, 0]],
            'modified' => [['date']],
            'thumbnail' => ['default' => new Image(), ['image']],
            'comics' => ['default' => new ResourceList(), ['resource-list']],
            'stories' => ['default' => new ResourceList(), ['resource-list']],
            'events' => ['default' => new ResourceList(), ['resource-list']],
            'characters' => ['default' => new ResourceList(), ['resource-list']],
            'creators' => ['default' => new ResourceList(), ['resource-list']],
            'next' => ['default' => new Summary(), ['summary']],
            'previous' => ['default' => new Summary(), ['summary']],
        ];
    }
}
