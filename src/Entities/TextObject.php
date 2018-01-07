<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * Represents a Marvel API TextObject Entity
 *
 * @property-read string $type The string description of the text object (e.g. solicit text, preview text, etc.).
 * @property-read string $language A language code denoting which language the text object is written in.
 * @property-read string $text The text of the text object.
 */
class TextObject extends AbstractEntity
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
            'language' => ['default' => null, ['string', true, 0]],
            'text' => ['default' => null, ['string', true, 0]],
        ];
    }
}
