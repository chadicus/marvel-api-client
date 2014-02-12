<?php
namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Util;

/**
 * Represents an Image entity type within the Marvel API.
 */
class Image
{
    /**
     * The directory path of to the image.
     *
     * @var string
     */
    private $path;

    /**
     * The file extension for the image.
     *
     * @var string
     */
    private $extension;

    /**
     * Construct a new instance of Image.
     *
     * @param string $path      The directory path of to the image.
     * @param string $extension The file extension for the image.
     */
    final public function __construct($path, $extension)
    {
         Util::throwIfNotType(['string' => [$path, $extension]], true, true);
         $this->path = $path;
         $this->extension = $extension;
    }

    /**
     * Returns the directory path of to the image.
     *
     * @return string
     */
    final public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the file extension for the image.
     *
     * @return string
     */
    final public function getExtension()
    {
         return $this->extension;
    }
}
