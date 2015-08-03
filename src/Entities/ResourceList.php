<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * Represents a Marvel API ResourceList Entity
 *
 * @property-read integer $available The number of total available resources in this list
 * @property-read integer $returned The number of resources returned in this resource list (up to 20).
 * @property-read string $collectionURI The path to the list of full view representations of the items in this resource list.
 * @property-read Summary[] $items A list of summary views of the items in this resource list.
*/
class ResourceList extends AbstractEntity
{
    /**
     * @see AbstractEntity::getFilters()
     *
     * @return array
     */
    final protected function getFilters()
    {
        return [
            'available' => [['int', true]],
            'returned' => [['int', true]],
            'collectionURI' => [['string', true, 0]],
            'items' => ['default' => [], ['summaries']],
        ];
    }
}
