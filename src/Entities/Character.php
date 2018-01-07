<?php

namespace Chadicus\Marvel\Api\Entities;

use Chadicus\Marvel\Api;
use DominionEnterprises\Util;

/**
 * Represents a Marvel API Character Entity
 *
 * @property-read integer $id The unique ID of the character resource.
 * @property-read string $name The name of the character.
 * @property-read string $description A short bio or description of the character.
 * @property-read DateTime $modified The date the resource was most recently modified.
 * @property-read string $resourceURI The canonical URL identifier for this resource.
 * @property-read Url[] $urls A set of public web site URLs for the resource.
 * @property-read Image $thumbnail The representative image for this character.
 * @property-read ResourceList $comics A resource list containing comics which feature this character.
 * @property-read ResourceList $stories A resource list of stories in which this character appears.
 * @property-read ResourceList $events A resource list of events in which this character appears.
 * @property-read ResourceList $series A resource list of series in which this character appears.
 */
class Character extends AbstractEntity
{
    /**
     * @see AbstractEntity::getFilters()
     *
     * @return array
     */
    final protected function getFilters() : array
    {
        return [
            'id' => [['int', true]],
            'name' => ['default' => '', ['string', true, 0]],
            'description' => ['default' => '', ['string', true, 0]],
            'modified' => [['date']],
            'resourceURI' => ['default' => '', ['string', true, 0]],
            'urls' => ['default' => [], ['_urls']],
            'thumbnail' => ['default' => new Image(), ['image']],
            'comics' => ['default' => new ResourceList(), ['resource-list']],
            'stories' => ['default' => new ResourceList(), ['resource-list']],
            'events' => ['default' => new ResourceList(), ['resource-list']],
            'series' => ['default' => new ResourceList(), ['resource-list']],
        ];
    }

    /**
     * Find all characters based on the given $criteria.
     *
     * @param Api\Client $client   The API Client.
     * @param array      $criteria The criteria to search with.
     *
     * @return Api\Collection
     */
    public static function findAll(Api\Client $client, array $criteria = []) : Api\Collection
    {
        $filters = [
            'name' => [['string']],
            'modifiedSince' => [['date', true], ['date-format', 'c']],
            'comics' => [['ofScalars', [['uint']]], ['implode', ',']],
            'series' => [['ofScalars', [['uint']]], ['implode', ',']],
            'events' => [['ofScalars', [['uint']]], ['implode', ',']],
            'stories' => [['ofScalars', [['uint']]], ['implode', ',']],
            'orderBy' => [['in', ['name', 'modified', '-name', '-modified']]],
        ];
        list($success, $filteredCriteria, $error) = Api\Filterer::filter($filters, $criteria);
        Util::ensure(true, $success, $error);

        return new Api\Collection($client, 'characters', $filteredCriteria);
    }
}
