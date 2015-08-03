<?php

namespace Chadicus\Marvel\Api\Entities;

use Chadicus\Marvel\Api\Filterer;
use Chadicus\Spl\Exceptions\UndefinedPropertyException;
use DominionEnterprises\Util;

abstract class AbstractEntity implements EntityInterface
{
    /**
     * The data for this AbstractEntity
     *
     * @var array
     */
    private $data = [];

    /**
     * Create a new AbstractEntity based on the given $input array
     *
     * @param array $input The data for the EntityInterface
     */
    public function __construct(array $input = [])
    {
        list($success, $filteredInput, $error) = Filterer::filter($this->getFilters(), $input, ['allowUnknowns' => true]);
        Util::ensure(true, $success, '\InvalidArgumentException', [$error]);

        foreach ($filteredInput as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    /**
     * Get an AbstractEntity property
     *
     * @param string $name The name of the property
     *
     * @return mixed
     */
    final public function __get($name)
    {
        if (!array_key_exists($name, $this->data)) {
            $class = get_called_class();
            throw new UndefinedPropertyException("Undefined Property {$class}::\${$name}");
        }

        return $this->data[$name];
    }

    /**
     * Allows for getX() method calls.
     *
     * @param string $name The name of the method being called.
     * @param array  $arguments The arguments being passed to the method. This parameter is unused.
     *
     * @return mixed
     *
     * @throws \BadMethodCallException Thrown if the property being accessed does not exist
     */
    final public function __call($name, array $arguments = [])
    {
        if (substr($name, 0, 3) !== 'get') {
            $class = get_called_class();
            throw new \BadMethodCallException("Method {$class}::{$name}() does not exist");
        }

        $key = lcfirst(substr($name, 3));

        if (!array_key_exists($key, $this->data)) {
            $class = get_called_class();
            throw new \BadMethodCallException("Method {$class}::{$name}() does not exist");
        }

        return $this->data[$key];
    }

    /**
     * Create an array of new AbstractEntity based on the given $input arrays
     *
     * @param array[] $inputs The value to be filtered.
     *
     * @return AbstractEntity[]
     */
    final public static function fromArrays(array $inputs)
    {
        Util::throwIfNotType(['array' => $inputs]);

        $entities = [];
        foreach ($inputs as $key => $input) {
            $entities[$key] = new static($input);
        }

        return $entities;
    }

    /**
     * Create a new AbstractEntity based on the given $input array
     *
     * @param array $input The data for the AbstractEntity
     *
     * @return AbstractEntity
     */
    final public static function fromArray(array $input)
    {
        return new static($input);
    }

    abstract protected function getFilters();
}
