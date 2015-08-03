<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * Represents a Marvel API Image Entity
 *
 * @property-read string $path The directory path of to the image.
 * @property-read string $extension The file extension for the image.
 */
class Image extends AbstractEntity
{
    /**
     * Returns the full image url.
     *
     * @param ImageVariant $variant The image variant to use in the url.
     *
     * @return string
     */
    final public function getUrl(ImageVariant $variant)
    {
        return "{$this->path}/{$variant}.{$this->extension}";
    }

    /**
     * @see AbstractEntity::getFilters()
     *
     * @return array
     */
    final protected function getFilters()
    {
        return [
            'path' => ['default' => null, ['string', true, 0]],
            'extension' => ['default' => null, ['string', true, 0]],
        ];
    }
}
