<?php

namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Filterer;
use DominionEnterprises\Util;

/**
 * Resource lists are collections of summary views within the context of another entity type.
 */
class ResourceList
{
    /**
     * The number of total available resources in this list.
     *
     * @var integer
     */
    private $available;

    /**
     * The number of resources returned in this resource list (up to 20).
     *
     * @var integer
     */
    private $returned;

    /**
     * The path to the list of full view representations of the items in this resource list.
     *
     * @var string
     */
    private $collectionURI;

    /**
     * A list of summary views of the items in this resource list.
     *
     * @var array[]
     */
    private $items;

    /**
     * Construct a new ResourceList.
     *
     * @param integer $available     The number of total available resources in this list.
     * @param integer $returned      The number of resources returned in this resource list (up to 20).
     * @param string  $collectionURI The path to the list of full view representations of the items in this resource
     *                               list.
     * @param array   $items         An array of Summary objects of the items in this resource list.
     */
    final public function __construct($available, $returned, $collectionURI, array $items = [])
    {
        Util::throwIfNotType(['int' => [$available, $returned], 'string' => [$collectionURI]], false, true);

        $this->available = $available;
        $this->returned = $returned;
        $this->collectionURI = $collectionURI;
        $this->items = $items;
    }

    /**
     * Returns the number of total available resources in this list.
     *
     * @return integer
     */
    final public function getAvailable()
    {
        return $this->available;
    }

    /**
     * Returns the number of resources returned in this resource list (up to 20).
     *
     * @return integer
     */
    final public function getReturned()
    {
        return $this->returned;
    }

    /**
     * Returns the path to the list of full view representations of the items in this resource list.
     *
     * @return string
     */
    final public function getCollectionURI()
    {
        return $this->collectionURI;
    }

    /**
     * Returns the list of summary views of the items in this resource list.
     *
     * @return Summary[]
     */
    final public function getItems()
    {
        return $this->items;
    }

    /**
     * Filters the given array $input into a ResourceList.
     *
     * @param array $input The value to be filtered.
     *
     * @return ResourceList
     *
     * @throws \Exception Thrown if the input did not pass validation.
     */
    final public static function fromArray(array $input)
    {
        $filters = [
            'available' => ['default' => 0, ['uint']],
            'returned' => ['default' => 0, ['uint']],
            'collectionURI' => ['default' => null, ['string']],
            'items' => ['default' => [], ['array', 0], ['\Chadicus\Marvel\Api\Entities\Summary::fromArrays']],
        ];

        list($success, $result, $error) = Filterer::filter($filters, $input);
        Util::ensure(true, $success, $error);

        return new ResourceList($result['available'], $result['returned'], $result['collectionURI'], $result['items']);
    }
}
