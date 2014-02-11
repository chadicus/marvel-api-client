<?php
namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Util;

/**
 * Represents a Url entity type within the Marvel API.
 */
class Url
{
    /**
     * A text identifier for the URL.
     *
     * @var string
     */
    private $type;

    /**
     * A full URL (including scheme, domain, and path).
     *
     * @var string
     */
    private $url;

    /**
     * Construct a new instance of Url.
     *
     * @param string $type The text identifier for the URL.
     * @param string $url  The full URL (including scheme, domain, and path).
     */
    final public function __construct($type, $url)
    {
         Util::throwIfNotType(['string' => [$type, $url]], true, true);
         $this->type = $type;
         $this->url = $url;
    }

    /**
     * Returns the text identifier for the URL.
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
     * @return string
     */
    final public function getUrl()
    {
        return $this->url;
    }
}
