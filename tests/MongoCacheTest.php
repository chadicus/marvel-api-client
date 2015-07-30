<?php
namespace Chadicus\Marvel\Api;

/**
 * Defines unit tests for the MongoCache class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\MongoCache
 */
final class MongoCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tear down each test.
     *
     * @return void
     */
    public function tearDown()
    {
        GlobalFunctions::reset();
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
        if (!\extension_loaded('mongo')) {
            $this->markTestSkipped('The mongo extension not available');
            return;
        }

        (new MongoCache(self::_getMongoCollection()))->set(new Request('not under test', 'not under test', [], []), new Response(200, [], []), 0);
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
        if (!\extension_loaded('mongo')) {
            $this->markTestSkipped('The mongo extension not available');
            return;
        }

        (new MongoCache(self::_getMongoCollection()))->set(new Request('not under test', 'not under test', [], []), new Response(200, [], []), Cache::MAX_TTL + 1);
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
        if (!\extension_loaded('mongo')) {
            $this->markTestSkipped('The mongo extension not available');
            return;
        }

        $cache = new MongoCache(self::_getMongoCollection());
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
        if (!\extension_loaded('mongo')) {
            $this->markTestSkipped('The mongo extension not available');
            return;
        }

        GlobalFunctions::$time = function () {
            return strtotime('-1 year');
        };

        $collection = self::_getMongoCollection();
        $cache = new MongoCache($collection);
        $request = new Request('not under test', 'not under test', [], []);
        $cache->set($request, new Response(200, [], []));
        $this->assertNotNull($cache->get($request));
        $endTime = \time() + 121;
        while (\time() <= $endTime) {
            if ($collection->count() === 0) {
                break;
            }

            \sleep(1);
        }

        $this->assertNull($cache->get($request));
    }

    /**
     * Verify MongoCache cannot be instantiated when the mongo extension is not loaded.
     *
     * @test
     * @covers ::__construct
     * @expectedException \RuntimeException
     * @expectedExceptionMessage The mongo extension is required for MongoCache
     *
     * @return void
     */
    public function constructMongoNotLoaded()
    {
        if (!\extension_loaded('mongo')) {
            $this->markTestSkipped('The mongo extension not available');
            return;
        }

        GlobalFunctions::$extensionLoaded = function ($name) {
            return false;
        };

        new MongoCache(self::_getMongoCollection());
    }

    /**
     * Verify construct throws with invalid parameters.
     *
     * @param mixed $collection        The collection containing the cached data.
     * @param mixed $defaultTimeToLive The default time to live in seconds.
     *
     * @test
     * @covers ::__construct
     * @dataProvider badConstructorData
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $defaultTimeToLive must be an integer >= 1 and <= 86400
     *
     * @return void
     */
    public function constructWithBadData($collection, $defaultTimeToLive)
    {
        if (!\extension_loaded('mongo')) {
            $this->markTestSkipped('The mongo extension not available');
            return;
        }

        new MongoCache($collection, $defaultTimeToLive);
    }

    /**
     * Data provider for constructWithBadData.
     *
     * @return array
     */
    public function badConstructorData()
    {
        if (!\extension_loaded('mongo')) {
            return [[null, null]];
        }

        return [
            'defaultTimeToLive is not an integer' => [self::_getMongoCollection(), 'a string'],
            'defaultTimeToLive is less than 1' => [self::_getMongoCollection(), 0],
            'defaultTimeToLive is greater than Cache::MAX_TTL' => [self::_getMongoCollection(), Cache::MAX_TTL + 1],
            'defaultTimeToLive is null' => [self::_getMongoCollection(), null],
        ];
    }

    /**
     * Helper method to get a mongo collection for testing.
     *
     * @return \MongoCollection
     */
    private static function _getMongoCollection()
    {
        $collection = (new \MongoClient())->selectDb('testing')->selectCollection('cache');
        $collection->drop();
        return $collection;
    }
}
