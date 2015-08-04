<?php
namespace Chadicus\Marvel\Api\Cache;

use Chadicus\Marvel\Api\Request;
use Chadicus\Marvel\Api\Response;

/**
 * Defines unit tests for the MongoCache class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Cache\MongoCache
 */
final class MongoCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * set up each test.
     *
     * @return void
     */
    public function setUp()
    {
        \Chadicus\FunctionRegistry::reset(__NAMESPACE__, ['date', 'Core']);
    }

    /**
     * Tear down each test.
     *
     * @return void
     */
    public function tearDown()
    {
        \Chadicus\FunctionRegistry::reset(__NAMESPACE__, ['date', 'Core']);
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

        (new MongoCache(self::getMongoCollection()))->set(
            new Request('not under test', 'not under test', [], []),
            new Response(200, [], []),
            0
        );
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

        (new MongoCache(self::getMongoCollection()))->set(
            new Request('not under test', 'not under test', [], []),
            new Response(200, [], []),
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
        if (!\extension_loaded('mongo')) {
            $this->markTestSkipped('The mongo extension not available');
            return;
        }

        $cache = new MongoCache(self::getMongoCollection());
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

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'time',
            function () {
                return strtotime('-1 year');
            }
        );

        $collection = self::getMongoCollection();
        $cache = new MongoCache($collection);
        $request = new Request('not under test', 'not under test', [], []);
        $cache->set($request, new Response(200, [], []));
        $this->assertNotNull($cache->get($request));
        $endTime = \time() + 60;
        while (\time() <= $endTime) {
            if ($collection->count() === 0) {
                break;
            }

            \usleep(500000);
        }

        if ($collection->count() !== 0) {
            $this->markTestSkipped('Mongo index took too long');
            return;
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

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'extension_loaded',
            function ($name) {
                return false;
            }
        );

        new MongoCache(self::getMongoCollection());
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
            'defaultTimeToLive is not an integer' => [self::getMongoCollection(), 'a string'],
            'defaultTimeToLive is less than 1' => [self::getMongoCollection(), 0],
            'defaultTimeToLive is greater than CacheInterface::MAX_TTL' => [
                self::getMongoCollection(),
                CacheInterface::MAX_TTL + 1
            ],
            'defaultTimeToLive is null' => [self::getMongoCollection(), null],
        ];
    }

    /**
     * Helper method to get a mongo collection for testing.
     *
     * @return \MongoCollection
     */
    private static function getMongoCollection()
    {
        $collection = (new \MongoClient())->selectDb('testing')->selectCollection('cache');
        $collection->drop();
        $collection->ensureIndex(['expires' => 1], ['expireAfterSeconds' => 0]);
        return $collection;
    }
}
