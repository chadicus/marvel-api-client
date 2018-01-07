<?php

namespace Chadicus\Marvel\Api;

/**
 * @coversDefaultClass \Chadicus\Marvel\Api\DataWrapper
 * @covers ::<protected>
 * @covers ::__construct
 */
final class DataWrapperTest extends \PHPUnit\Framework\TestCase
{
    /**
     * DataWrapper instance to use in tests.
     *
     * @var DataWrapper
     */
    private $dataWrapper;

    /**
     * Prepare each test
     *
     * @return void
     */
    public function setUp()
    {
        $input = [
            'code' => 1,
            'status' => 'a status',
            'copyright' => 'a copyright',
            'attributionText' => 'a attributionText',
            'attributionHTML' => 'a attributionHTML',
            'etag' => 'a etag',
            'data' =>  [
                'offset' => 9,
                'limit' => 6,
                'total' => 3,
                'count' => 4,
                'results' => [['id' => 1, 'resourceURI' => Client::BASE_URL . 'characters/1']],
            ],
        ];

        $this->dataWrapper = new DataWrapper($input, 'characters');
    }

    /**
     * Verify basic behaviour of getCode().
     *
     * @test
     * @covers ::getCode
     *
     * @return void
     */
    public function getCode()
    {
        $this->assertSame(1, $this->dataWrapper->getCode());
    }

    /**
     * Verify basic behaviour of getStatus().
     *
     * @test
     * @covers ::getStatus
     *
     * @return void
     */
    public function getStatus()
    {
        $this->assertSame('a status', $this->dataWrapper->getStatus());
    }

    /**
     * Verify basic behaviour of getCopyright().
     *
     * @test
     * @covers ::getCopyright
     *
     * @return void
     */
    public function getCopyright()
    {
        $this->assertSame('a copyright', $this->dataWrapper->getCopyright());
    }

    /**
     * Verify basic behaviour of getAttributionText().
     *
     * @test
     * @covers ::getAttributionText
     *
     * @return void
     */
    public function getAttributionText()
    {
        $this->assertSame('a attributionText', $this->dataWrapper->getAttributionText());
    }

    /**
     * Verify basic behaviour of getAttributionHTML().
     *
     * @test
     * @covers ::getAttributionHTML
     *
     * @return void
     */
    public function getAttributionHTML()
    {
        $this->assertSame('a attributionHTML', $this->dataWrapper->getAttributionHTML());
    }

    /**
     * Verify basic behaviour of getETag().
     *
     * @test
     * @covers ::getETag
     *
     * @return void
     */
    public function getETag()
    {
        $this->assertSame('a etag', $this->dataWrapper->getETag());
    }

    /**
     * Verify basic behaviour of getData().
     *
     * @test
     * @covers ::getData
     *
     * @return void
     */
    public function getData()
    {
        $data = $this->dataWrapper->getData();
        $this->assertSame(9, $data->getOffset());
        $this->assertSame(6, $data->getLimit());
        $this->assertSame(3, $data->getTotal());
        $this->assertSame(4, $data->getCount());
        $this->assertSame(1, count($data->getResults()));
        $characters = $data->getResults();
        $this->assertInstanceOf('\\Chadicus\\Marvel\\Api\\Entities\\Character', $characters[0]);
        $this->assertSame(1, $characters[0]->getId());
    }

    /**
     * Verify basic behaviour of fromJson().
     *
     * @test
     * @covers ::fromJson
     *
     * @return void
     */
    public function fromJson()
    {
        $input = [
            'code' => 1,
            'status' => 'a status',
            'copyright' => 'a copyright',
            'attributionText' => 'a attributionText',
            'attributionHTML' => 'a attributionHTML',
            'etag' => 'a etag',
            'data' =>  [
                'offset' => 9,
                'limit' => 6,
                'total' => 3,
                'count' => 4,
                'results' => [['id' => 1, 'resourceURI' => Client::BASE_URL . 'characters/1']],
            ],
        ];

        $dataWrapper = DataWrapper::fromJson(json_encode($input));
        $this->assertSame($input['code'], $dataWrapper->getCode());
        $this->assertSame($input['status'], $dataWrapper->getStatus());
        $this->assertSame($input['copyright'], $dataWrapper->getCopyright());
        $this->assertSame($input['attributionText'], $dataWrapper->getAttributionText());
        $this->assertSame($input['attributionHTML'], $dataWrapper->getAttributionHTML());
        $this->assertSame($input['etag'], $dataWrapper->getETag());

        $data = $this->dataWrapper->getData();
        $this->assertSame($input['data']['offset'], $data->getOffset());
        $this->assertSame($input['data']['limit'], $data->getLimit());
        $this->assertSame($input['data']['total'], $data->getTotal());
        $this->assertSame($input['data']['count'], $data->getCount());
        $this->assertSame(1, count($data->getResults()));
        $characters = $data->getResults();
        $this->assertInstanceOf('\Chadicus\Marvel\Api\Entities\Character', $characters[0]);
        $this->assertSame(1, $characters[0]->getId());
    }
}
