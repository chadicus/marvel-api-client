<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * Represents a Marvel API Url Entity
 *
 * @property-read string $type A text identifier for the URL.
 * @property-read string $url A full URL (including scheme, domain, and path).
 */
class Url extends AbstractEntity
{
    /**
     * @see AbstractEntity::getFilters()
     *
     * @return array
     */
    final protected function getFilters() : array
    {
        return [
            'type' => ['default' => null, ['string', true, 0]],
            'url' => ['default' => null, ['string', true, 0]],
        ];
    }
}
