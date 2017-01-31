<?php
namespace Chadicus\Marvel\Api\Cache;

use Zend\Diactoros\Request;
use Zend\Diactoros\Response;

/**
 * Defines unit tests for the ArrayCache class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Cache\ArrayCache
 */
final class ArrayCacheTest extends \PHPUnit_Framework_TestCase
{
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
     * Verify cache is removed when expired.
     *
     * @test
     * @covers ::set
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage TTL value must be an integer >= 1 and <= 86400
     *
     * @return void
     */
    public function setTtlIsLessThanOne()
    {
        (new ArrayCache())->set(new Request(), new Response(), -1);
    }

    /**
     * Verify cache is removed when expired.
     *
     * @test
     * @covers ::set
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage TTL value must be an integer >= 1 and <= 86400
     *
     * @return void
     */
    public function setTtlIsGreaterThanMax()
    {
        (new ArrayCache())->set(
            new Request(),
            new Response(),
            CacheInterface::MAX_TTL + 1
        );
    }

    /**
     * Verify cache is removed when expired.
     *
     * @test
     * @covers ::get
     *
     * @return void
     */
    public function getNotFound()
    {
        $cache = new ArrayCache();
        $request = new Request();
        $this->assertNull($cache->get($request));
    }

    /**
     * Verify cache is removed when expired.
     *
     * @test
     * @covers ::__construct
     * @covers ::set
     * @covers ::get
     *
     * @return void
     */
    public function getExpired()
    {
        $cache = new ArrayCache();
        $request = new Request();
        $cache->set($request, new Response());
        $this->assertNotNull($cache->get($request));

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'time',
            function () {
                return strtotime('+1 year');
            }
        );

        $this->assertNull($cache->get($request));
    }

    /**
     * Verify basic functionality of clear().
     *
     * @test
     * @covers ::clear
     *
     * @return void
     */
    public function clear()
    {
        $cache = new ArrayCache();
        $request = new Request();
        $cache->set($request, new Response());
        $this->assertNotNull($cache->get($request));
        $cache->clear();
        $this->assertNull($cache->get($request));
    }

    /**
     * Verify construct throws with invalid parameters.
     *
     * @param integer $defaultTimeToLive The default time to live in seconds.
     *
     * @test
     * @covers ::__construct
     * @dataProvider badConstructorData
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage TTL value must be an integer >= 1 and <= 86400
     *
     * @return void
     */
    public function constructWithBadData($defaultTimeToLive)
    {
        new ArrayCache($defaultTimeToLive);
    }

    /**
     * Data provider for constructWithBadData.
     *
     * @return array
     */
    public function badConstructorData()
    {
        return [
            'defaultTimeToLive is not an integer' => ['a string'],
            'defaultTimeToLive is less than 1' => [-1],
            'defaultTimeToLive is greater than CacheInterface::MAX_TTL' => [CacheInterface::MAX_TTL + 1],
            'defaultTimeToLive is null' => [null],
        ];
    }
}
