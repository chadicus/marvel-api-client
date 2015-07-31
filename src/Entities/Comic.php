<?php
namespace Chadicus\Marvel\Api\Entities;

use DominionEnterprises\Filterer;
use Chadicus\Marvel\Api;
use DominionEnterprises\Util;

/**
 * Represents the Marvel API Comic entity.
 */
class Comic
{
    const API_RESOURCE = 'comics';

    /**
     * The unique ID of the comic resource.
     *
     * @var integer
     */
    private $id;

    /**
     * The ID of the digital comic representation of this comic. Will be 0 if the comic is not available digitally.
     *
     * @var integer
     */
    private $digitalId;

    /**
     * The canonical title of the comic.
     *
     * @var string
     */
    private $title;

    /**
     * The number of the issue in the series (will generally be 0 for collection formats).
     *
     * @var integer
     */
    private $issueNumber;

    /**
     * If the issue is a variant (e.g. an alternate cover, second printing, or director's cut), a text description of
     * the variant.
     *
     * @var string
     */
    private $variantDescription;

    /**
     * The preferred description of the comic.
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
     * The ISBN for the comic (generally only populated for collection formats).
     *
     * @var string
     */
    private $isbn;

    /**
     * The UPC barcode number for the comic (generally only populated for periodical formats).
     *
     * @var string
     */
    private $upc;

    /**
     * The Diamond code for the comic.
     *
     * @var string
     */
    private $diamondCode;

    /**
     * The EAN barcode for the comic.
     *
     * @var string
     */
    private $ean;

    /**
     * The ISSN barcode for the comic.
     *
     * @var string
     */
    private $issn;

    /**
     * The publication format of the comic e.g. comic, hardcover, trade paperback.
     *
     * @var string
     */
    private $format;

    /**
     * The number of story pages in the comic.
     *
     * @var integer
     */
    private $pageCount;

    /**
     * A set of descriptive text blurbs for the comic.
     *
     * @var TextObject[]
     */
    private $textObjects;

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
     * A summary representation of the series to which this comic belongs.
     *
     * @var ResourceList
     */
    private $series;

    /**
     * A list of variant issues for this comic (includes the "original" issue if the current issue is a variant).
     *
     * @var ComicSummary[]
     */
    private $variants;

    /**
     * A list of collections which include this comic (will generally be empty if the comic's format is a collection).
     *
     * @var ComicSummary[]
     */
    private $collections;

    /**
     * A list of issues collected in this comic (will generally be empty for periodical formats such as "comic" or
     * "magazine").
     *
     * @var ComicSummary[]
     */
    private $collectedIssues;

    /**
     * A list of key dates for this comic.
     *
     * @var Date[]
     */
    private $dates;

    /**
     * A list of prices for this comic.
     *
     * @var Price[]
     */
    private $prices;

    /**
     * The representative image for this comic.
     *
     * @var Image
     */
    private $thumbnail;

    /**
     * A list of promotional images associated with this comic.
     *
     * @var Image[]
     */
    private $images;

    /**
     * A resource list containing the creators associated with this comic.
     *
     * @var ResourceList
     */
    private $creators;

    /**
     * A resource list containing the characters which appear in this comic.
     *
     * @var ResourceList
     */
    private $characters;

    /**
     * A resource list containing the stories which appear in this comic.
     *
     * @var ResourceList
     */
    private $stories;

    /**
     * A resource list containing the events in which this comic appears.
     *
     * @var ResourceList
     */
    private $events;

    /**
     * Construct a new instance of a Comic.
     *
     * @param array $data   The values for this Comic.
     */
    final public function __construct(array $data)
    {
        $emptyResourceList = new ResourceList(0, 0, null, []);
        $emptySummary = new Summary(null, null);

        $filters = [
            'id' => [['int', true]],
            'digitalId' => [['int', true]],
            'title' => [['string', true, 0]],
            'issueNumber' => [['int', true]],
            'variantDescription' => [['string', true, 0]],
            'description' => [['string', true, 0]],
            'modified' => [['date', true]],
            'isbn' => [['string', true, 0]],
            'upc' => [['string', true, 0]],
            'diamondCode' => [['string', true, 0]],
            'ean' => [['string', true, 0]],
            'issn' => [['string', true, 0]],
            'format' => [['string', true, 0]],
            'pageCount' => [['int', true]],
            'textObjects' => [['\Chadicus\Marvel\Api\Entities\TextObject::fromArrays']],
            'resourceURI' => [['string', true, 0]],
            'urls' => [['\Chadicus\Marvel\Api\Entities\Url::fromArrays']],
            'series' => ['default' => $emptySummary, ['\Chadicus\Marvel\Api\Entities\Summary::fromArray']],
            'events' => ['default' => $emptyResourceList, ['\Chadicus\Marvel\Api\Entities\ResourceList::fromArray']],
            'stories' => ['default' => clone $emptyResourceList, ['\Chadicus\Marvel\Api\Entities\ResourceList::fromArray']],
            'creators' => ['default' => clone $emptyResourceList, ['\Chadicus\Marvel\Api\Entities\ResourceList::fromArray']],
            'characters' => ['default' => clone $emptyResourceList, ['\Chadicus\Marvel\Api\Entities\ResourceList::fromArray']],
            'variants' => ['default' => [], ['\Chadicus\Marvel\Api\Entities\Summary::fromArrays']],
            'collections' => ['default' => [], ['\Chadicus\Marvel\Api\Entities\Summary::fromArrays']],
            'collectedIssues' => ['default' => [], ['\Chadicus\Marvel\Api\Entities\Summary::fromArrays']],
            'dates' => [['\Chadicus\Marvel\Api\Entities\Date::fromArrays']],
            'prices' => [['\Chadicus\Marvel\Api\Entities\Price::fromArrays']],
            'thumbnail' => [['\Chadicus\Marvel\Api\Entities\Image::fromArray']],
            'images' => ['default' => [], ['\Chadicus\Marvel\Api\Entities\Image::fromArrays']],
        ];

        list($success, $filteredData, $error) = Filterer::filter($filters, $data, ['allowUnknowns' => true]);
        Util::ensure(true, $success, $error);

        foreach ($filteredData as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * The unique ID of the comic resource.
     *
     * @return integer
     */
    final public function getId()
    {
        return $this->id;
    }

    /**
     * The ID of the digital comic representation of this comic. Will be 0 if the comic is not available digitally.
     *
     * @return integer
     */
    final public function getDigitalId()
    {
        return $this->digitalId;
    }

    /**
     * The canonical title of the comic.
     *
     * @return string
     */
    final public function getTitle()
    {
        return $this->title;
    }

    /**
     * The number of the issue in the series (will generally be 0 for collection formats).
     *
     * @return integer
     */
    final public function getIssueNumber()
    {
        return $this->issueNumber;
    }

    /**
     * If the issue is a variant (e.g. an alternate cover, second printing, or director's cut), a text description of
     * the variant.
     *
     * @return string
     */
    final public function getVariantDescription()
    {
        return $this->variantDescription;
    }

    /**
     * The preferred description of the comic.
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
     * The ISBN for the comic (generally only populated for collection formats).
     *
     * @return string
     */
    final public function getIsbn()
    {
        return $this->isbn;
    }

    /**
     * The UPC barcode number for the comic (generally only populated for periodical formats).
     *
     * @return string
     */
    final public function getUpc()
    {
        return $this->upc;
    }

    /**
     * The Diamond code for the comic.
     *
     * @return string
     */
    final public function getDiamondCode()
    {
        return $this->diamondCode;
    }

    /**
     * The EAN barcode for the comic.
     *
     * @return string
     */
    final public function getEan()
    {
        return $this->ean;
    }

    /**
     * The ISSN barcode for the comic.
     *
     * @return string
     */
    final public function getIssn()
    {
        return $this->issn;
    }

    /**
     * The publication format of the comic e.g. comic, hardcover, trade paperback.
     *
     * @return string
     */
    final public function getFormat()
    {
        return $this->format;
    }

    /**
     * The number of story pages in the comic.
     *
     * @return integer
     */
    final public function getPageCount()
    {
        return $this->pageCount;
    }

    /**
     * A set of descriptive text blurbs for the comic.
     *
     * @return TextObject[]
     */
    final public function getTextObjects()
    {
        return $this->textObjects;
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
     * A set of public web site URLs for the resource.
     *
     * @return Url[]
     */
    final public function getUrls()
    {
        return $this->urls;
    }

    /**
     * A summary representation of the series to which this comic belongs.
     *
     * @return SeriesSummary
     */
    final public function getSeries()
    {
        return $this->series;
    }

    /**
     * A list of variant issues for this comic (includes the "original" issue if the current issue is a variant).
     *
     * @return ComicSummary[]
     */
    final public function getVariants()
    {
        return $this->variants;
    }

    /**
     * A list of collections which include this comic (will generally be empty if the comic's format is a collection).
     *
     * @return ComicSummary[]
     */
    final public function getCollections()
    {
        return $this->collections;
    }

    /**
     * A list of issues collected in this comic (will generally be empty for periodical formats such as "comic" or
     * "magazine").
     *
     * @return ComicSummary[]
     */
    final public function getCollectedIssues()
    {
        return $this->collectedIssues;
    }

    /**
     * A list of key dates for this comic.
     *
     * @return ComicDate[]
     */
    final public function getDates()
    {
        return $this->dates;
    }

    /**
     * A list of prices for this comic.
     *
     * @return ComicPrice[]
     */
    final public function getPrices()
    {
        return $this->prices;
    }

    /**
     * The representative image for this comic.
     *
     * @return Image
     */
    final public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * A list of promotional images associated with this comic.
     *
     * @return Image[]
     */
    final public function getImages()
    {
        return $this->images;
    }

    /**
     * A resource list containing the creators associated with this comic.
     *
     * @return ResourceList
     */
    final public function getCreators()
    {
        return $this->creators;
    }

    /**
     * A resource list containing the characters which appear in this comic.
     *
     * @return ResourceList
     */
    final public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * A resource list containing the stories which appear in this comic.
     *
     * @return ResourceList
     */
    final public function getStories()
    {
        return $this->stories;
    }

    /**
     * A resource list containing the events in which this comic appears.
     *
     * @return ResourceList
     */
    final public function getEvents()
    {
        return $this->events;
    }

    /**
     * Returns a collection containing all Comics which match the given criteria.
     *
     * @param Api\Client $client   The API Client.
     * @param array      $criteria The criteria for searching.
     *
     * @return Api\Collection
     */
    final public static function findAll(Api\Client $client, array $criteria = [])
    {
        $boolToStringFilter = function ($bool) {
            return $bool ? 'true' : 'false';
        };

        $formatDateFilter = function ($date) {
            return $date !== null ? $date->format('c') : null;
        };

        $filters = [
            'format' => [
                [
                    'in',
                    [
                        'comic',
                        'hardcover',
                        'trade paperback',
                        'magazine',
                        'digest',
                        'graphic novel',
                        'digital comic',
                        'infinite comic',
                    ]
                ],
            ],
            'formatType' => [['in', ['comic', 'collection']]],
            'noVariants' => [['bool'], [$boolToStringFilter]],
            'dateDescriptor' => [['in', ['lastWeek', 'thisWeek', 'nextWeek', 'thisMonth']]],
            'fromDate' => [['date', true]],
            'toDate' => [['date', true]],
            'hasDigitalIssue' => [['bool'], [$boolToStringFilter]],
            'modifiedSince' => [['date', true], [$formatDateFilter]],
            'creators' => [['ofScalars', [['uint']]], ['implode', ',']],
            'characters' => [['ofScalars', [['uint']]], ['implode', ',']],
            'series' => [['ofScalars', [['uint']]], ['implode', ',']],
            'events' => [['ofScalars', [['uint']]], ['implode', ',']],
            'stories' => [['ofScalars', [['uint']]], ['implode', ',']],
            'sharedAppearances' => [['ofScalars', [['uint']]], ['implode', ',']],
            'collaborators' => [['ofScalars', [['uint']]], ['implode', ',']],
            'orderBy' => [
                [
                    'in',
                    [
                        'focDate',
                        'onsaleDate',
                        'title',
                        'issueNumber',
                        'modified',
                        '-focDate',
                        '-onsaleDate',
                        '-title',
                        '-issueNumber',
                        '-modified',
                    ],
                ]
            ],

        ];

        list($success, $filteredCriteria, $error) = Filterer::filter($filters, $criteria);
        Util::ensure(true, $success, $error);

        $modifiedSince = Util\Arrays::get($filteredCriteria, 'modifiedSince');

        $toDate = Util\Arrays::get($filteredCriteria, 'toDate');
        $fromDate = Util\Arrays::get($filteredCriteria, 'fromDate');
        if ($toDate !== null && $fromDate !== null) {
            unset($filteredCriteria['toDate'], $filteredCriteria['fromDate']);
            $filteredCriteria['dateRange'] = "{$fromDate->format('c')},{$toDate->format('c')}";
        }

        return new Api\Collection(
            $client,
            self::API_RESOURCE,
            $filteredCriteria,
            function (array $data) use ($client) {
                return new Comic($data);
            }
        );
    }
}
