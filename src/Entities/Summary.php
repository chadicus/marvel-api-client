<?php
namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Util;
use DominionEnterprises\Filterer;

/**
 * Represents the Summary entity of the Marvel API.
 */
class Summary extends AbstractEntity
{
    /**
     * The path to the individual entity resource.
     *
     * @var string
     */
    private $resourceURI;

    /**
     * The canonical name of the entity.
     *
     * @var string
     */
    private $name;

    /**
     * The role of the creator in the parent entity.
     *
     * @var string
     */
    private $role;

    /**
     * The type of the entity.
     *
     * @var string
     */
    private $type;

    /**
     * Create a new instance of Summary.
     *
     * @param string      $resourceURI The path to the individual entity resource.
     * @param string      $name        The canonical name of the entity.
     * @param string|null $role        The role of the creator in the parent entity.
     * @param string|null $type        The type of the entity.
     */
    final public function __construct($resourceURI, $name, $role = null, $type = null)
    {
        Util::throwIfNotType(['string' => [$resourceURI, $name, $role, $type]], true, true);
        $this->resourceURI = $resourceURI;
        $this->name = $name;
        $this->role = $role;
        $this->type = $type;
    }

    /**
     * Returns the path to the individual entity resource.
     *
     * @return string
     */
    final public function getResourceURI()
    {
        return $this->resourceURI;
    }

    /**
     * Returns the canonical name of the entity.
     *
     * @return string
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the role of the creator in the parent entity.
     *
     * @return string
     */
    final public function getRole()
    {
        return $this->role;
    }

    /**
     * Returns the type of the entity.
     *
     * @return string
     */
    final public function getType()
    {
        return $this->type;
    }

    /**
     * Filters the given array $input into a Summary.
     *
     * @param array $input The value to be filtered.
     *
     * @return Summary
     *
     * @throws \Exception Thrown if the input did not pass validation.
     */
    final public static function fromArray(array $input)
    {
        $filters = [
            'resourceURI' => ['default' => null, ['string', true]],
            'name' => ['default' => null, ['string', true]],
            'type' => ['default' => null, ['string', true]],
            'role' => ['default' => null, ['string', true]],
        ];

        list($success, $result, $error) = Filterer::filter($filters, $input);
        Util::ensure(true, $success, $error);

        return new Summary($result['resourceURI'], $result['name'], $result['role'], $result['type']);
    }
}
