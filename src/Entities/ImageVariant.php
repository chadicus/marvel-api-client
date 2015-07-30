<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * Class representing all image variant options.
 */
final class ImageVariant
{
    const PORTRAIT_SMALL = 'portrait_small';
    const PORTRAIT_MEDIUM = 'portrait_medium';
    const PORTRAIT_XLARGE = 'portrait_xlarge';
    const PORTRAIT_FANTASTIC = 'portrait_fantastic';
    const PORTRAIT_UNCANNY = 'portrait_uncanny';
    const PORTRAIT_INCREDIBLE = 'portrait_incredible';
    const STANDARD_SMALL = 'standard_small';
    const STANDARD_MEDIUM = 'standard_medium';
    const STANDARD_LARGE = 'standard_large';
    const STANDARD_XLARGE = 'standard_xlarge';
    const STANDARD_FANTASTIC = 'standard_fantastic';
    const STANDARD_AMAZING = 'standard_amazing';
    const LANDSCAPE_SMALL = 'landscape_small';
    const LANDSCAPE_MEDIUM = 'landscape_medium';
    const LANDSCAPE_LARGE = 'landscape_large';
    const LANDSCAPE_XLARGE = 'landscape_xlarge';
    const LANDSCAPE_AMAZING = 'landscape_amazing';
    const LANDSCAPE_INCREDIBLE = 'landscape_incredible';

    /**
     * The current Enum value.
     *
     * @var string
     */
    private $value;

    /**
     * Construct a new instance of a Enum object.
     *
     * @param string $value The value of the enum.
     */
    private function __construct($value)
    {
        $this->value = strtolower($value);
    }

    /**
     * Returns the string value of the Enum.
     *
     * @return string The string value.
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * Returns a new instance of ImageVariant.
     *
     * @param string $name      The name of the constant.
     * @param array  $arguments This parameter is not used.
     *
     * @return ImageVariant
     *
     * @throws \InvalidArgumentException Thrown if $name is not the value of a defined constant.
     *
     * @SuppressWarnings("unused")
     */
    public static function __callStatic($name, array $arguments = [])
    {
        if (!\defined(__CLASS__ . "::{$name}")) {
            throw new \InvalidArgumentException("Invalid value '{$name}' given");
        }

        return new static($name);
    }
}
