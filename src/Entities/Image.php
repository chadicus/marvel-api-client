<?php
namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Util;
use DominionEnterprises\Util\Arrays;

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

    /**
     * Returns the full image url.
     *
     * @param ImageVariant $variant The image variant to use in the url.
     *
     * @return string
     */
    final public function getUrl(ImageVariant $variant)
    {
        return "{$this->getPath()}/{$variant}.{$this->getExtension()}";
    }

    /**
     * Filters the given array $input into a Image.
     *
     * @param array $input The value to be filtered.
     *
     * @return Image
     *
     * @throws \Exception Thrown if the input did not pass validation.
     */
    final public static function fromArray(array $input)
    {
        $filters = ['path' => [['string']], 'extension' => [['string']]];

        list($success, $result, $error) = \DominionEnterprises\Filterer::filter($filters, $input);
        if (!$success) {
            throw new \Exception($error);
        }

        return new Image(Arrays::get($result, 'path'), Arrays::get($result, 'extension'));
    }

    /**
     * Filters the given array[] $inputs into Image[].
     *
     * @param array[] $inputs The value to be filtered.
     *
     * @return Image[]
     *
     * @throws \Exception Thrown if the inputs did not pass validation.
     */
    final public static function fromArrays(array $inputs)
    {
        Util::throwIfNotType(['array' => $inputs]);

        $images = [];
        foreach ($inputs as $key => $input) {
            $images[$key] = self::fromArray($input);
        }

        return $images;
    }
}
