<?php

namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Filterer;
use DominionEnterprises\Util;

/**
 * Represents a ComicPrice entity type within the Marvel API.
 *
 * @property-read string type A description of the price (e.g. print price, digital price).,
 * @property-read float price The price (all prices in USD).
 */
class Price extends AbstractEntity
{
    /**
     * @see AbstractEntity::getFilters()
     *
     * @return array
     */
    final protected function getFilters()
    {
        return [
            'type' => ['default' => null, ['string', true]],
            'price' => ['default' => null, ['float', true, null, null, true]]
        ];
    }
}
