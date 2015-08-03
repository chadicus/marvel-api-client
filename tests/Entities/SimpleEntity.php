<?php

namespace Chadicus\Marvel\Api\Entities;

class SimpleEntity extends AbstractEntity
{
    public $input;

    protected function getFilters()
    {
        return ['field' => [['string']]];
    }
}

