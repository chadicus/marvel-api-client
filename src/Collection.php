<?php
namespace Chadicus\Marvel\Api;

use DominionEnterprises\Util;

/**
 * Class for iterating index response to the Marvel API. Collections are readonly.
 */
class Collection implements \Iterator, \Countable
{
    /**
     * API Client.
     *
     * @var Client
     */
    private $client;

    /**
     * Limit to give to API.
     *
     * @var integer
     */
    private $limit;

    /**
     * Offset to give to API.
     *
     * @var integer
     */
    private $offset;

    /**
     * Resource name for collection.
     *
     * @var string
     */
    private $resource;

    /**
     * Array of filters to pass to API.
     *
     * @var array
     */
    private $filters;

    /**
     * Total number of elements in the collection.
     *
     * @var integer
     */
    private $total;

    /**
     * Pointer in the paginated results.
     *
     * @var integer
     */
    private $position;

    /**
     * A paginated set of elements from the API.
     *
     * @var array
     */
    private $results;

    /**
     * A custom callable to return a defined type when iterating over the collection.
     *
     * @var callable
     */
    private $loader;

    /**
     * Create a new collection.
     *
     * @param Client   $client   A client connection to the API.
     * @param string   $resource The name of API resource to request.
     * @param array    $filters  A key value pair array of search filters.
     * @param callable $loader   A custom callable to use when iterating over the collection.
     */
    final public function __construct(Client $client, $resource, array $filters = [], callable $loader = null)
    {
        Util::throwIfNotType(array('string' => array($resource)), true);

        $this->client = $client;
        $this->resource = $resource;
        $this->filters = $filters;
        $this->loader = $loader;
        $this->rewind();
    }

    /**
     * Return the count elements in this collection, @see Countable::count().
     *
     * @return integer
     */
    final public function count()
    {
        if ($this->position === -1) {
            $this->next();
        }

        return $this->total;
    }

    /**
     * Rewind the Iterator to the first element, @see Iterator::rewind().
     *
     * @return void
     */
    final public function rewind()
    {
        $this->results = null;
        $this->offset = 0;
        $this->total = 0;
        $this->limit = 0;
        $this->position = -1;
    }

    /**
     * Return the key of the current element, @see Iterator::key().
     *
     * @return integer
     */
    final public function key()
    {
        if ($this->position === -1) {
            $this->next();
        }

        Util::ensure(false, empty($this->results), '\OutOfBoundsException', array('Collection contains no elements'));

        return $this->offset + $this->position;
    }

    /**
     * Checks if current position is valid, @see Iterator::valid().
     *
     * @return boolean
     */
    final public function valid()
    {
        if ($this->position === -1) {
            $this->next();
        }

        return $this->offset + $this->position < $this->total;
    }

    /**
     * Move forward to next element, @see Iterator::next().
     *
     * @return void
     */
    final public function next()
    {
        ++$this->position;

        if ($this->position < $this->limit) {
            return;
        }

        $this->offset += $this->limit;
        $this->filters['offset'] = $this->offset;
        $this->filters['limit'] = $this->limit === 0 ? 20 : $this->limit;
        $indexResponse = $this->client->search($this->resource, $this->filters);

        $httpCode = $indexResponse->getHttpCode();
        Util::ensure(200, $httpCode, "Did not receive 200 from API. Instead received {$httpCode}");

        $response = $indexResponse->getBody();
        $this->limit = $response['data']['limit'];
        $this->total = $response['data']['total'];
        $this->results = $response['data']['results'];
        $this->position = 0;
    }

    /**
     * Return the current element, @see Iterator::current().
     *
     * @return mixed Returns the element in the results array or a custom type defined by $loader.
     */
    final public function current()
    {
        if ($this->position === -1) {
            $this->next();
        }

        Util::ensure(
            true,
            array_key_exists($this->position, $this->results),
            '\OutOfBoundsException',
            ['Collection contains no element at current position']
        );

        if ($this->loader === null) {
            return $this->results[$this->position];
        }

        return call_user_func_array($this->loader, [$this->results[$this->position]]);
    }
}
