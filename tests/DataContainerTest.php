<?php

namespace Chadicus\Marvel\Api;

/**
 * @coversDefaultClass \Chadicus\Marvel\Api\DataContainer
 * @covers ::<private>
 * @covers ::__construct
 */
final class DataContainerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verifies basic behaviour of the DataContainer class
     *
     * @test
     * @covers ::getOffset
     * @covers ::getLimit
     * @covers ::getTotal
     * @covers ::getCount
     * @covers ::getResults
     *
     * @return void
     */
    public function basicUsage()
    {
        $data = [
            'offset' => 0,
            'limit' => 1,
            'total' => 1,
            'count' => 1,
            'results' => [['id' => 1, 'resourceURI' => Client::BASE_URL . 'characters/1']],
        ];

        $container = new DataContainer($data);
        $this->assertSame($data['offset'], $container->getOffset());
        $this->assertSame($data['limit'], $container->getLimit());
        $this->assertSame($data['total'], $container->getTotal());
        $this->assertSame($data['count'], $container->getCount());
        $this->assertSame(1, count($container->getResults()));
        $characters = $container->getResults();
        $this->assertInstanceOf('\Chadicus\Marvel\Api\Entities\Character', $characters[0]);
        $this->assertSame(1, $characters[0]->getId());
    }

    /**
     * Verifies behaviour of __construct when $input is empty.
     *
     * @test
     *
     * @return void
     */
    public function constructDefaults()
    {
        $container = new DataContainer([]);
        $this->assertSame(0, $container->getOffset());
        $this->assertSame(0, $container->getLimit());
        $this->assertSame(0, $container->getTotal());
        $this->assertSame(0, $container->getCount());
        $this->assertSame([], $container->getResults());
    }

    /**
     * Verifies behaviour of __construct when $input['results']['resourceURI'] is null.
     *
     * @test
     *
     * @return void
     */
    public function constructNullResourceURI()
    {
        $container = new DataContainer(
            [
                'results' => [
                    [
                        'resourceURI' => null,
                    ],
                ],
            ]
        );
        $this->assertSame([], $container->getResults());
    }

    /**
     * Verifies behaviour of __construct when $input['results']['resourceURI'] does not match the regex pattern.
     *
     * @test
     *
     * @return void
     */
    public function constructInvalidResourceURI()
    {
        $container = new DataContainer(
            [
                'results' => [
                    [
                        'resourceURI' => 'http://example.com',
                    ],
                ],
            ]
        );
        $this->assertSame([], $container->getResults());
    }
}
