<?php
namespace Chadicus\Filter;

/**
 * A collection of filters for filtering strings into \DateTime objects.
 */
class DateTime
{
    /**
     * Filters the given value into a \DateTime object.
     *
     * @param integer|string $value The value to be filtered.
     *
     * @return \DateTime
     *
     * @throws Exception Thrown if the value did not pass validation.
     */
    public static function filter($value)
    {
        if (is_int($value)) {
            $value = "@{$value}";
        }

        if (!is_string($value) || trim($value) == '') {
            throw new Exception('$value is not a string or integer');
        }

        try {
            return new \DateTime($value);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
