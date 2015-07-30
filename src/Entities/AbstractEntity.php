<?php

namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Util;

abstract class AbstractEntity implements EntityInterface
{
    /**
     * Filters the given array[] $inputs into Price[].
     *
     * @param array[] $inputs The value to be filtered.
     *
     * @return Price[]
     *
     * @throws \Exception Thrown if the inputs did not pass validation.
     */
    final public static function fromArrays(array $inputs)
    {
        Util::throwIfNotType(['array' => $inputs]);

        $entities = [];
        foreach ($inputs as $key => $input) {
            $entities[$key] = static::fromArray($input);
        }

        return $entities;
    }
}
