<?php

namespace Chadicus\Marvel\Api;

use DominionEnterprises\Util;

class DataContainer
{
    /**
     * The requested offset (number of skipped results) of the call.
     *
     * @var integer
     */
    private $offset;

    /**
     * The requested result limit.
     *
     * @var integer
     */
    private $limit;

    /**
     * The total number of resources available given the current filter set.
     *
     * @var integer
     */
    private $total;

    /**
     * The total number of results returned by this call.
     *
     * @var integer
     */
    private $count;

    /**
     * The list of creators returned by the call.
     *
     * @var EntityInterface[]
     */
    private $results = [];

    /**
     * Create a new DataContainer instance.
     *
     * @param array $input The data for the DataContainer
     */
    public function __construct(array $input)
    {
        $resourceFilter = self::deriveResourceFilter(Util\Arrays::get($input, 'results', []));

        $filters = [
            'offset' => ['default' => 0, ['int', true]],
            'limit' => ['default' => 0, ['int', true]],
            'total' => ['default' => 0, ['int', true]],
            'count' => ['default' => 0, ['int', true]],
            'results' => [[$resourceFilter]],
        ];

        list($success, $filtered, $error) = Filterer::filter($filters, $input, ['allowUnknowns' => true]);
        Util::ensure(true, $success, $error);

        foreach ($filtered as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Helper method to derive the filter to use for the given resource array
     *
     * @param mixed $results the results array from the API
     *
     * @return callable The filter to use
     */
    private static function deriveResourceFilter($results)
    {
        $default = function () {
            return [];
        };

        if (!is_array($results) || !isset($results[0]['resourceURI'])) {
            return $default;
        }

        $pattern = '^' . preg_quote(Client::BASE_URL) . '(?P<resource>[a-z]*)/\d+$';
        $matches = [];
        preg_match("#{$pattern}#", $results[0]['resourceURI'], $matches);
        return Util\Arrays::get($matches, 'resource', $default);
    }

    /**
     * Returns The requested offset (number of skipped results) of the call.
     *
     * @return integer
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Returns The requested result limit.
     *
     * @return integer
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Returns The total number of resources available given the current filter set.
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Returns The total number of results returned by this call.
     *
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Returns The list of creators returned by the call.
     *
     * @return EntityInterface[]
     */
    public function getResults()
    {
        return $this->results;
    }
}
