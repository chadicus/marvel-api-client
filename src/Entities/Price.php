<?php

namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Filterer;
use DominionEnterprises\Util;

/**
 * Represents a ComicPrice entity type within the Marvel API.
 */
class Price extends AbstractEntity
{
    /**
     * A description of the price (e.g. print price, digital price).
     *
     * @var string
     */
    private $type;

    /**
     * The price (all prices in USD).
     *
     * @var float
     */
    private $price;

    /**
     * Construct a new instance of Price.
     *
     * @param string $type   The description of the price.
     * @param float  $price The price of the price.
     */
    final public function __construct($type, $price)
    {
         Util::throwIfNotType(['string' => [$type], 'float' => [$price]], true, true);
         $this->type = $type;
         $this->price = $price;
    }

    /**
     * Returns the description of the price.
     *
     * @return string
     */
    final public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the price in USD.
     *
     * @return float
     */
    final public function getPrice()
    {
        return $this->price;
    }

    /**
     * Filters the given array $input into a Price.
     *
     * @param array $input The value to be filtered.
     *
     * @return Price
     *
     * @throws \Exception Thrown if the input did not pass validation.
     */
    final public static function fromArray(array $input)
    {
        $filters = [
            'type' => ['default' => null, ['string', true]],
            'price' => ['default' => null, ['float', true]]];

        list($success, $result, $error) = Filterer::filter($filters, $input);
        Util::ensure(true, $success, $error);

        return new Price($result['type'], $result['price']);
    }
}
