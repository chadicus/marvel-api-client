<?php

namespace Chadicus\Marvel\Api\Entities;

use Chadicus\Marvel\Api\Filterer;
use Chadicus\Spl\Exceptions\UndefinedPropertyException;
use Chadicus\Spl\Exceptions\NotAllowedException;
use DominionEnterprises\Util;

/**
 * Base entity class.
 */
abstract class AbstractEntity implements EntityInterface, \ArrayAccess
{
    /**
     * The data for this AbstractEntity
     *
     * @var array
     */
    private $data = [];

    /**
     * Create a new AbstractEntity based on the given $input array.
     *
     * @param array $input The data for the EntityInterface.
     */
    public function __construct(array $input = [])
    {
        list($success, $filtered, $error) = Filterer::filter($this->getFilters(), $input, ['allowUnknowns' => true]);
        Util::ensure(true, $success, '\InvalidArgumentException', [$error]);

        foreach ($filtered as $key => $value) {
            $this->data[$key] = $value;
        }
    }

    /**
     * Get an AbstractEntity property.
     *
     * @param string $name The name of the property.
     *
     * @return mixed
     *
     * @throws UndefinedPropertyException Throw if this class does not contain the $name'd property.
     */
    final public function __get(string $name)
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
     * @param string $name      The name of the method being called.
     * @param array  $arguments The arguments being passed to the method. This parameter is unused.
     *
     * @return mixed
     *
     * @throws \BadMethodCallException Thrown if the property being accessed does not exist.
     */
    final public function __call(string $name, array $arguments = [])
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
     * @param array $input The data for the AbstractEntity.
     *
     * @return AbstractEntity
     */
    final public static function fromArray(array $input)
    {
        return new static($input);
    }

    /**
     * Returns whether the requested index exists
     *
     * @param string $offset The index being checked.
     *
     * @return boolean
     */
    final public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * Returns the value at the specified index.
     *
     * @param string $offset The index with the value.
     *
     * @return mixed
     */
    final public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * Sets the value at the specified index to newval
     *
     * @param string $offset The index being get.
     * @param mixed  $value  The new value for the index.
     *
     * @return void
     *
     * @throws NotAllowedException Ensure this object is immutable.
     */
    final public function offsetSet($offset, $value)
    {
        $class = get_called_class();
        throw new NotAllowedException("{$class}::\${$offset} is read-only");
    }

    /**
     * Unsets the value at the specified index
     *
     * @param string $offset The index being unset.
     *
     * @return void
     *
     * @throws NotAllowedException Ensure this object is immutable.
     */
    final public function offsetUnset($offset)
    {
        $class = get_called_class();
        throw new NotAllowedException("{$class}::\${$offset} is read-only");
    }

    /**
     * Returns an array of filters suitable for use with \Chadicus\Marvel\Api\Filterer::filter()
     *
     * @return array
     */
    abstract protected function getFilters();
}
