<?php

namespace Chadicus\Marvel\Api;

use DominionEnterprises\Util;

/**
 * Object representation of the API result data wrapper.
 */
final class DataWrapper implements DataWrapperInterface
{
    /**
     * The HTTP status code of the returned result.
     *
     * @var integer
     */
    private $code;

    /**
     * A string description of the call status.
     *
     * @var string
     */
    private $status;

    /**
     * The copyright notice for the returned result.
     *
     * @var string
     */
    private $copyright;

    /**
     * The attribution notice for this result
     *
     * @var string
     */
    private $attributionText;

    /**
     * An HTML representation of the attribution notice for this result.
     *
     * @var string
     */
    private $attributionHTML;

    /**
     * A digest value of the content returned by the call.
     *
     * @var string
     */
    private $etag;

    /**
     * The results returned by the call.
     *
     * @var DataContainerInterface
     */
    private $data;

    /**
     * Create a new DataWrapper instance.
     *
     * @param array $input The data for the DataWrapper.
     */
    public function __construct(array $input)
    {
        $filters = [
            'code' => [['int', true]],
            'status' => [['string', true]],
            'copyright' => [['string', true]],
            'attributionText' => [['string', true]],
            'attributionHTML' => [['string', true]],
            'etag' => [['string', true]],
            'data' => ['default' => [], ['array', 0]],
        ];

        list($success, $filteredInput, $error) = Filterer::filter($filters, $input, ['allowUnknowns' => true]);
        Util::ensure(true, $success, $error);

        foreach ($filteredInput as $key => $value) {
            $this->$key = $value;
        }

        $this->data = new DataContainer($filteredInput['data']);
    }

    /**
     * Construct a DataWrapper from the given JSON string.
     *
     * @param string $json The json encoded data.
     *
     * @return static
     */
    public static function fromJson(string $json)
    {
        return new static(json_decode($json, true));
    }

    /**
     * Returns the HTTP status code of the returned result.
     *
     * @return integer
     */
    public function getCode() : int
    {
        return $this->code;
    }

    /**
     * Returns A string description of the call status.
     *
     * @return string
     */
    public function getStatus() : string
    {
        return $this->status;
    }

    /**
     * Returns the copyright notice for the returned result.
     *
     * @return string
     */
    public function getCopyright() : string
    {
        return $this->copyright;
    }

    /**
     * Returns the attribution notice for this result
     *
     * @return string
     */
    public function getAttributionText() : string
    {
        return $this->attributionText;
    }

    /**
     * Returns an HTML representation of the attribution notice for this result.
     *
     * @return string
     */
    public function getAttributionHTML() : string
    {
        return $this->attributionHTML;
    }

    /**
     * Returns a digest value of the content returned by the call.
     *
     * @return string
     */
    public function getEtag() : string
    {
        return $this->etag;
    }

    /**
     * Returns the results returned by the call.
     *
     * @return DataContainerInterface
     */
    public function getData() : DataContainerInterface
    {
        return $this->data;
    }
}
