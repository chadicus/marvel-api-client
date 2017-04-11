<?php

namespace Chadicus\Marvel\Api\Cache;

use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

/**
 * @coversDefaultClass Chadicus\Marvel\Api\Cache\NullCache
 * @covers ::<private>
 * @covers ::<protected>
 */
final class NullCacheTest extends \PHPUnit\Framework\TestCase
{
    /**
     * NullCache instance to use in tests.
     *
     * @var NullCache
     */
    private $cache;

    /**
     * Prepare each test
     *
     * @return void
     */
    public function setUp()
    {
        $this->cache = new NullCache();
    }

    /**
     * Verify basic behavior of get().
     *
     * @test
     * @covers ::get
     *
     * @return void
     */
    public function get()
    {
        $default = new \StdClass();
        $this->assertSame($default, $this->cache->get('a key', $default));
    }

    /**
     * Verify basic behavior of set().
     *
     * @test
     * @covers ::set
     *
     * @return void
     */
    public function set()
    {
        $this->assertTrue($this->cache->set('a key', $this->getResponse()));
    }

    /**
     * Verify basic behavior of delete().
     *
     * @test
     * @covers ::delete
     *
     * @return void
     */
    public function delete()
    {
        $this->assertTrue($this->cache->delete('a key'));
    }

    /**
     * Verify basic behavior of clear().
     *
     * @test
     * @covers ::clear
     *
     * @return void
     */
    public function clear()
    {
        $this->assertTrue($this->cache->clear());
    }

    /**
     * Verify basic behavior of getMultple().
     *
     * @test
     * @covers ::getMultiple
     *
     * @return void
     */
    public function getMultiple()
    {
        $default = new \StdClass();
        $this->assertSame(
            ['key1' => $default, 'key2' => $default],
            $this->cache->getMultiple(['key1', 'key2'], $default)
        );
    }

    /**
     * Verify basic behavior of setMultiple().
     *
     * @test
     * @covers ::setMultiple
     *
     * @return void
     */
    public function setMultiple()
    {
        $this->assertTrue($this->cache->setMultiple(['key' => $this->getResponse(), 'key2' => $this->getResponse()]));
    }

    /**
     * Verify basic behavior of deleteMultiple().
     *
     * @test
     * @covers ::deleteMultiple
     *
     * @return void
     */
    public function deleteMultiple()
    {
        $this->assertTrue($this->cache->deleteMultiple(['key1', 'key2']));
    }

    /**
     * Verify basic behavior of has().
     *
     * @test
     * @covers ::has
     *
     * @return void
     */
    public function has()
    {
        $this->assertFalse($this->cache->has('key1'));
    }

    /**
     * Helper method to create a Response instance to use in tests.
     *
     * @return Response
     */
    private function getResponse() : Response
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, json_encode(['status' => 'ok']));

        return new Response(
            new Stream($stream),
            200,
            ['Content-type' => 'application/json', 'etag' => '"an etag"']
        );
    }
}
