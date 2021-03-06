<?php
namespace Chadicus\Marvel\Api\Entities;

/**
 * Represents a Date entity type within the Marvel API.
 *
 * @property-read string $type A description of the date (e.g. onsale date, FOC date).
 * @property-read \DateTime $date The date.
 */
class Date extends AbstractEntity
{
    /**
     * @see AbstractEntity::getFilters()
     *
     * @return array
     */
    final protected function getFilters() : array
    {
        return [
            'type' => ['default' => null, ['string', true]],
            'date' => ['default' => null, ['date', true]],
        ];
    }
}
