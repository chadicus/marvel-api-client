<?php

namespace Chadicus\Marvel\Api;

/**
 * @coversDefaultClass \Chadicus\Marvel\Api\DataWrapper
 * @covers ::<protected>
 * @covers ::__construct
 */
final class DataWrapperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic behaviour of the DataWrapper class
     *
     * @test
     * @covers ::getCode
     * @covers ::getStatus
     * @covers ::getCopyright
     * @covers ::getAttributionText
     * @covers ::getAttributionHTML
     * @covers ::getETag
     * @covers ::getData
     *
     * @return void
     */
    public function basicUsage()
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

        $entityWrapper = new DataWrapper($input, 'characters');
        $this->assertSame($input['code'], $entityWrapper->getCode());
        $this->assertSame($input['status'], $entityWrapper->getStatus());
        $this->assertSame($input['copyright'], $entityWrapper->getCopyright());
        $this->assertSame($input['attributionText'], $entityWrapper->getAttributionText());
        $this->assertSame($input['attributionHTML'], $entityWrapper->getAttributionHTML());
        $this->assertSame($input['etag'], $entityWrapper->getETag());

        $data = $entityWrapper->getData();
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
