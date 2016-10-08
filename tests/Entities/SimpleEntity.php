<?php

namespace Chadicus\Marvel\Api\Entities;

/**
 * Entity objects for testing.
 */
class SimpleEntity extends AbstractEntity
{
    /**
     * @see AbstractEntity::getFilters().
     *
     * @return array
     */
    protected function getFilters()
    {
        return ['field' => [['string']]];
    }
}
