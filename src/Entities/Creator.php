<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * Represents a Marvel API Creator Entity
 *
 * @property-read integer $id The unique ID of the creator resource.
 * @property-read string $firstName The first name of the creator.
 * @property-read string $middleName The middle name of the creator.
 * @property-read string $lastName The last name of the creator.
 * @property-read string $suffix The suffix or honorific for the creator.
 * @property-read string $fullName The full name of the creator (a space-separated concatenation of the above four fields).
 * @property-read DateTime $modified The date the resource was most recently modified.
 * @property-read string $resourceURI The canonical URL identifier for this resource.
 * @property-read Url[] $urls A set of public web site URLs for the resource.
 * @property-read Image $thumbnail The representative image for this creator.
 * @property-read ResourceList $series A resource list containing the series which feature work by this creator.
 * @property-read ResourceList $stories A resource list containing the stories which feature work by this creator.
 * @property-read ResourceList $comics A resource list containing the comics which feature work by this creator.
 * @property-read ResourceList $events A resource list containing the events which feature work by this creator.
*/
class Creator extends AbstractEntity
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
            'firstName' => [['string', true, 0]],
            'middleName' => [['string', true, 0]],
            'lastName' => [['string', true, 0]],
            'suffix' => [['string', true, 0]],
            'fullName' => [['string', true, 0]],
            'modified' => [['date']],
            'resourceURI' => [['string', true, 0]],
            'urls' => ['default' => [], ['_urls']],
            'thumbnail' => ['default' => new Image(), ['image']],
            'series' => ['default' => new ResourceList(), ['resource-list']],
            'stories' => ['default' => new ResourceList(), ['resource-list']],
            'comics' => ['default' => new ResourceList(), ['resource-list']],
            'events' => ['default' => new ResourceList(), ['resource-list']],
        ];
    }
}
