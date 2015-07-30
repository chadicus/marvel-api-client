<?php

namespace Chadicus\Marvel\Api\Entities;

class SimpleEntity extends AbstractEntity
{
    public $input;

    public static function fromArray(array $input)
    {
        $self = new self();
        $self->input = $input;
        return $self;
    }
}

