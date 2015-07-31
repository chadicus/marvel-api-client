<?php
namespace Chadicus\Marvel\Api\Entities;

use DateTime;
use DominionEnterprises\Filterer;
use DominionEnterprises\Util;

/**
 * Represents a Date entity type within the Marvel API.
 */
class Date extends AbstractEntity implements EntityInterface
{
    /**
     * A description of the date (e.g. onsale date, FOC date).
     *
     * @var string
     */
    private $type;

    /**
     * The date
     *
     * @var DateTime|null
     */
    private $date;

    /**
     * Construct a new instance of Date.
     *
     * @param string        $type The text identifier for the URL.
     * @param DateTime|null $date The full URL (including scheme, domain, and path).
     */
    final public function __construct($type, DateTime $date = null)
    {
         $this->type = $type;
         $this->date = $date;
    }

    /**
     * Returns a description of the date (e.g. onsale date, FOC date).
     *
     * @return string
     */
    final public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the full URL (including scheme, domain, and path).
     *
     * @return DateTime
     */
    final public function getDate()
    {
        return $this->date;
    }

    /**
     * Filters the given array $input into a Date.
     *
     * @param array $input The value to be filtered.
     *
     * @return Date
     */
    final public static function fromArray(array $input)
    {
        $filters = [
            'type' => ['default' => null, ['string', true]],
            'date' => ['default' => null, ['date', true]],
        ];

        list($success, $result, $error) = Filterer::filter($filters, $input);
        Util::ensure(true, $success, $error);

        return new static($result['type'], $result['date']);
    }
}
