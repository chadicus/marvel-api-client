<?php
namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Util;
use DominionEnterprises\Filterer;

/**
 * Represents the Summary entity of the Marvel API.
 *
 * @property-read string $resourceURI The path to the individual api entity
 * @property-read string $name The canonical name of the api entity
 * @property-read string $type The type of the api entity (ex. interior or cover for story)
 * @property-read string $role The role of the api entity (ex. writer or artis for creator)
 */
class Summary extends AbstractEntity
{
    /**
     * @see AbstractEntity::getFilters()
     *
     * @return array
     */
    final protected function getFilters() : array
    {
        return [
            'resourceURI' => ['default' => null, ['string', true]],
            'name' => ['default' => null, ['string', true, 0]],
            'type' => ['default' => null, ['string', true, 0]],
            'role' => ['default' => null, ['string', true, 0]],
        ];
    }
}
