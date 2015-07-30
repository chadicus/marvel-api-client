<?php

namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Filterer;
use Chadicus\Marvel\Api\Client;
use Chadicus\Marvel\Api\Collection;
use DominionEnterprises\Util;
use DominionEnterprises\Util\Arrays;

/**
 * OO Representation of a Marvel Character.
 */
class Character
{
    /**
     * The unique ID of the character resource.
     *
     * @var integer
     */
    private $id;

    /**
     * The name of the character.
     *
     * @var string
     */
    private $name;

    /**
     * A short bio or description of the character.
     *
     * @var string
     */
    private $description;

    /**
     * The date the resource was most recently modified.
     *
     * @var \DateTime
     */
    private $modified;

    /**
     * The canonical URL identifier for this resource.
     *
     * @var string
     */
    private $resourceURI;

    /**
     * A set of public web site URLs for the resource.
     *
     * @var Url[]
     */
    private $urls;

    /**
     * The representative image for this character.
     *
     * @var Image
     */
    private $thumbnail;

    /**
     * A resource list containing comics which feature this character.
     *
     * @var ResourceList
     */
    private $comics;

    /**
     * A resource list of stories in which this character appears.
     *
     * @var ResourceList
     */
    private $stories;

    /**
     * A resource list of events in which this character appears.
     *
     * @var ResourceList
     */
    private $events;

    /**
     * A resource list of series in which this character appears.
     *
     * @var ResourceList
     */
    private $series;

    /**
     * Construct a new instance of a Character.
     *
     * @param array  $data   The values for this Character.
     */
    final public function __construct(array $data)
    {
        $emptyResourceList = new ResourceList(0, 0, null, []);
        $resourceListFilter = '\Chadicus\Marvel\Api\Entities\ResourceList::fromArray';
        $filters = [
            'id' => ['required' => true, ['uint']],
            'name' => [['string']],
            'description' => [['string']],
            'modified' => [['date']],
            'resourceURI' => [['string']],
            'urls' => ['default' => [], ['array', 0], ['\Chadicus\Marvel\Api\Entities\Url::fromArrays']],
            'thumbnail' => [
                'default' => new Image(null, null),
                ['array'],
                ['\Chadicus\Marvel\Api\Entities\Image::fromArray']
            ],
            'comics' => ['default' => $emptyResourceList, ['array'], [$resourceListFilter]],
            'stories' => ['default' => clone $emptyResourceList, ['array'], [$resourceListFilter]],
            'events' => ['default' => clone $emptyResourceList, ['array'], [$resourceListFilter]],
            'series' => ['default' => clone $emptyResourceList, ['array'], [$resourceListFilter]],
        ];

        list($status, $filteredData, $error) = Filterer::filter($filters, $data, ['allowUnknowns' => true]);
        Util::ensure(true, $status, $error);

        foreach ($filteredData as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * The unique ID of the character resource.
     *
     * @return integer
     */
    final public function getId()
    {
        return $this->id;
    }

    /**
     * The name of the character.
     *
     * @return string
     */
    final public function getName()
    {
        return $this->name;
    }

    /**
     * Return the short bio or description of the character.
     *
     * @return string
     */
    final public function getDescription()
    {
        return $this->description;
    }

    /**
     * The date the resource was most recently modified.
     *
     * @return \DateTime
     */
    final public function getModified()
    {
        return $this->modified;
    }

    /**
     * The canonical URL identifier for this resource.
     *
     * @return string
     */
    final public function getResourceURI()
    {
        return $this->resourceURI;
    }

    /**
     * Return the set of public web site URLs for the resource.
     *
     * @return Url[]
     */
    final public function getUrls()
    {
        return $this->urls;
    }

    /**
     * The representative image for this character.
     *
     * @return Image
     */
    final public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * Return the resource list containing comics which feature this character.
     *
     * @return ResourceList
     */
    final public function getComics()
    {
        return $this->comics;
    }

    /**
     * Return the resource list of stories in which this character appears.
     *
     * @return ResourceList
     */
    final public function getStories()
    {
        return $this->stories;
    }

    /**
     * Return the resource list of events in which this character appears.
     *
     * @return ResourceList
     */
    final public function getEvents()
    {
        return $this->events;
    }

    /**
     * Return the resource list of series in which this character appears.
     *
     * @return ResourceList
     */
    final public function getSeries()
    {
        return $this->series;
    }

    /**
     * Find all characters based on the given $criteria.
     *
     * @param Client $client   The API Client.
     * @param array  $criteria The criteria to search with.
     *
     * @return ResourceList
     */
    public static function findAll(Client $client, array $criteria = [])
    {
        $filters = [
            'name' => [['string']],
            'modifiedSince' => [['date']],
            'comics' => [['ofScalars', [['uint']]], ['implode', ',']],
            'series' => [['ofScalars', [['uint']]], ['implode', ',']],
            'events' => [['ofScalars', [['uint']]], ['implode', ',']],
            'stories' => [['ofScalars', [['uint']]], ['implode', ',']],
            'orderBy' => [['in', ['name', 'modified', '-name', '-modified']]],
        ];
        list($success, $filteredCriteria, $error) = Filterer::filter($filters, $criteria);
        Util::ensure(true, $success, $error);

        $modifiedSince = Arrays::get($filteredCriteria, 'modifiedSince');
        if ($modifiedSince !== null) {
            $filteredCriteria['modifiedSince'] = $modifiedSince->format('c');
        }

        return new Collection(
            $client,
            'characters',
            $filteredCriteria,
            function (array $data) {
                return new Character($data);
            }
        );
    }
}
