<?php
namespace Chadicus\Marvel\Api;

/**
 * Defines unit tests for the ArrayCache class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\ArrayCache
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
     * @expectedExceptionMessage $timeToLive must be an integer >= 1 and <= 86400
     *
     * @return void
     */
    public function setTtlIsLessThanOne()
    {
        (new ArrayCache())->set(new Request('not under test', 'not under test', [], []), new Response(200, [], []), 0);
    }

    /**
     * Verify cache is removed when expired.
     *
     * @test
     * @covers ::set
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $timeToLive must be an integer >= 1 and <= 86400
     *
     * @return void
     */
    public function setTtlIsGreaterThanMax()
    {
        (new ArrayCache())->set(
            new Request('not under test', 'not under test', [], []),
            new Response(200, [], []),
            Cache::MAX_TTL + 1
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
        $request = new Request('not under test', 'not under test', [], []);
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
        $request = new Request('not under test', 'not under test', [], []);
        $cache->set($request, new Response(200, [], []));
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
        $request = new Request('not under test', 'not under test', [], []);
        $cache->set($request, new Response(200, [], []));
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
     * @expectedExceptionMessage $defaultTimeToLive must be an integer >= 1 and <= 86400
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
            'defaultTimeToLive is less than 1' => [0],
            'defaultTimeToLive is greater than Cache::MAX_TTL' => [Cache::MAX_TTL + 1],
            'defaultTimeToLive is null' => [null],
        ];
    }
}
