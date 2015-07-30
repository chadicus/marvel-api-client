<?php
namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Util;
use DominionEnterprises\Util\Arrays;

/**
 * Represents a ComicPrice entity type within the Marvel API.
 */
class Price
{
    /**
     * A description of the price (e.g. print price, digital price).
     *
     * @var string
     */
    private $type;

    /**
     * The amount (all prices in USD).
     *
     * @var float
     */
    private $amount;

    /**
     * Construct a new instance of Price.
     *
     * @param string $type   The description of the price.
     * @param float  $amount The amount of the price.
     */
    final public function __construct($type, $amount)
    {
         Util::throwIfNotType(['string' => [$type], 'float' => [$amount]], true, true);
         $this->type = $type;
         $this->amount = $amount;
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
     * Returns the amount in USD.
     *
     * @return float
     */
    final public function getAmount()
    {
        return $this->amount;
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
        $filters = ['type' => [['string']], 'amount' => [['float']]];

        list($success, $result, $error) = \DominionEnterprises\Filterer::filter($filters, $input);
        if (!$success) {
            throw new \Exception($error);
        }

        return new Price(Arrays::get($result, 'type'), Arrays::get($result, 'amount'));
    }

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

        $prices = [];
        foreach ($inputs as $key => $input) {
            $prices[$key] = self::fromArray($input);
        }

        return $prices;
    }
}
