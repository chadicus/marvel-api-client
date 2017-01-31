<?php
namespace Chadicus\Marvel\Api\Cache;

use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use MongoDB\Collection;

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
     * @expectedExceptionMessage TTL value must be an integer >= 1 and <= 86400
     *
     * @return void
     */
    public function setTtlIsLessThanOne()
    {
        (new MongoCache(self::getMongoCollection()))->set(new Request(), new Response(), -1);
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
        (new MongoCache(self::getMongoCollection()))->set(new Request(), new Response(), CacheInterface::MAX_TTL + 1);
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
        $cache = new MongoCache(self::getMongoCollection());
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
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'time',
            function () {
                return strtotime('-1 year');
            }
        );

        $collection = self::getMongoCollection();
        $cache = new MongoCache($collection);
        $request = new Request();
        $cache->set($request, new Response('php://memory', 200));
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
     * Verify construct throws with invalid parameters.
     *
     * @param mixed $collection        The collection containing the cached data.
     * @param mixed $defaultTimeToLive The default time to live in seconds.
     *
     * @test
     * @covers ::__construct
     * @dataProvider badConstructorData
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage TTL value must be an integer >= 1 and <= 86400
     *
     * @return void
     */
    public function constructWithBadData($collection, $defaultTimeToLive)
    {
        new MongoCache($collection, $defaultTimeToLive);
    }

    /**
     * Data provider for constructWithBadData.
     *
     * @return array
     */
    public function badConstructorData()
    {
        return [
            'defaultTimeToLive is not an integer' => [self::getMongoCollection(), 'a string'],
            'defaultTimeToLive is less than 1' => [self::getMongoCollection(), -1],
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
     * @return \MongoDB\Collection
     */
    private static function getMongoCollection()
    {
        $collection = (new \MongoDB\Client())->selectDatabase('testing')->selectCollection('cache');
        $collection->drop();
        $collection->createIndex(['expires' => 1], ['expireAfterSeconds' => 0]);
        return $collection;
    }
}
