<?php

namespace Chadicus\Marvel\Api\Cache;

use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

/**
 * @coversDefaultClass Chadicus\Marvel\Api\Cache\ArrayCache
 * @covers ::<private>
 * @covers ::<protected>
 */
final class ArrayCacheTest extends \PHPUnit\Framework\TestCase
{
    /**
     * ArrayCache instance to use in tests.
     *
     * @var ArrayCache
     */
    private $cache;

    /**
     * Prepare each test
     *
     * @return void
     */
    public function setUp()
    {
        $this->cache = new ArrayCache();
    }

    /**
     * Tear down each test.
     *
     * @return void
     */
    public function tearDown()
    {
        \Chadicus\FunctionRegistry::reset(__NAMESPACE__, ['date']);
    }

    /**
     * Verify behavior of get() when key is not found.
     *
     * @test
     * @covers ::get
     *
     * @return void
     */
    public function getKeyNotFound()
    {
        $default = new \StdClass();
        $this->assertSame($default, $this->cache->get('key', $default));
    }

    /**
     * Verify behavior of get() when cache has expired.
     *
     * @test
     * @covers ::set
     * @covers ::get
     *
     * @return void
     */
    public function getExpired()
    {
        $default = new \StdClass();
        $item = $this->getResponse();
        $this->cache->set('key', $item, \DateInterval::createFromDateString('1 day'));

        $this->assertSame($item, $this->cache->get('key', $default));

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'time',
            function () {
                return \strtotime('+1 year');
            }
        );

        $this->assertSame($default, $this->cache->get('key', $default));
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
        $default = new \StdClass();
        $item = $this->getResponse();
        $this->cache->set('key', $item, 86400);
        $this->assertSame($item, $this->cache->get('key', $default));
        $this->assertTrue($this->cache->delete('key'));
        $this->assertSame($default, $this->cache->get('key', $default));
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
        $default = new \StdClass();
        $item = $this->getResponse();
        $this->cache->set('key1', $item);
        $this->cache->set('key2', $item);
        $this->assertSame($item, $this->cache->get('key1', $default));
        $this->assertSame($item, $this->cache->get('key2', $default));
        $this->assertTrue($this->cache->clear());
        $this->assertSame($default, $this->cache->get('key1', $default));
        $this->assertSame($default, $this->cache->get('key2', $default));
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
        $item = $this->getResponse();
        $this->cache->set('key1', $item);
        $this->assertSame(
            $this->cache->getMultiple(['key1', 'key2'], $default),
            ['key1' => $item, 'key2' => $default]
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
        $item1 = $this->getResponse();
        $item2 = $this->getResponse();
        $this->assertTrue($this->cache->setMultiple(['key1' => $item1, 'key2' => $item2]));
        $this->assertSame($item1, $this->cache->get('key1'));
        $this->assertSame($item2, $this->cache->get('key2'));
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
        $item1 = $this->getResponse();
        $item2 = $this->getResponse();
        $this->cache->setMultiple(['key1' => $item1, 'key2' => $item2]);
        $this->assertSame($item1, $this->cache->get('key1'));
        $this->assertSame($item2, $this->cache->get('key2'));
        $this->assertTrue($this->cache->deleteMultiple(['key1', 'key2']));
        $this->assertNull($this->cache->get('key1'));
        $this->assertNull($this->cache->get('key2'));
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
        $this->assertFalse($this->cache->has('key'));
        $this->cache->set('key', $this->getResponse());
        $this->assertTrue($this->cache->has('key'));
    }

    /**
     * Verify behavior of set() if invalid $ttl value is given.
     *
     * @test
     * @covers ::set
     * @expectedException \Psr\SimpleCache\InvalidArgumentException
     * @expectedExceptionMessage $ttl must be null, an integer or \DateInterval instance
     *
     * @return void
     */
    public function setInvalidTTL()
    {
        $this->cache->set('key', $this->getResponse(), new \DateTime());
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
