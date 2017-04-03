<?php
namespace Chadicus\Marvel\Api\Cache;

use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

/**
 * Defines unit tests for the MongoCache class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Cache\MongoCache
 * @covers ::__construct
 * @covers ::<private>
 * @covers ::<protected>
 */
final class MongoCacheTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Mongo Collection to use in tests.
     *
     * @var MongoDB\Collection
     */
    private $collection;

    /**
     * Cache instance to us in tests.
     *
     * @var MongoCache
     */
    private $cache;

    /**
     * set up each test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->collection = (new Client())->selectDatabase('testing')->selectCollection('cache');
        $this->collection->drop();
        $this->cache = new MongoCache($this->collection);
    }

    /**
     * Verify behavior of get() when the key is not found.
     *
     * @test
     * @covers ::get
     *
     * @return void
     */
    public function getNotFound()
    {
        $default = new \StdClass();
        $this->assertSame($default, $this->cache->get('key', $default));
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
        $json = json_encode(['status' => 'ok']);
        $headers = ['Content-Type' => ['application/json'], 'eTag' => ['"an etag"']];
        $this->collection->insertOne(
            [
                '_id' => 'key',
                'statusCode' => 200,
                'headers' => $headers,
                'body' => $json,
                'protocolVersion' => '1.1',
                'reasonPhrase' => 'OK',
                'expires' => new UTCDateTime(strtotime('+1 day') * 1000),
            ]
        );

        $response = $this->cache->get('key');
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($headers, $response->getHeaders());
        $this->assertSame($json, (string)$response->getBody());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame('OK', $response->getReasonPhrase());
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
        $response = $this->getResponse();
        $expires = \DateInterval::createFromDateString('1 day');
        $this->cache->set('key', $response, $expires);

        $actual = $this->collection->find(
            [],
            ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
        )->toArray();

        $expires = new UTCDateTime(strtotime('+1 day') * 1000);

        $this->assertResponseDocument('key', $expires, $response, $actual[0]);
    }

    /**
     * Verify behavior of set() with invalid $ttl value.
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
     * Verify behavior of set() with illegal $value.
     *
     * @test
     * @covers ::set
     * @expectedException \Psr\SimpleCache\InvalidArgumentException
     * @expectedExceptionMessage $value must be an instance of \Psr\Http\Message\ResponseInterface
     *
     * @return void
     */
    public function setInvalidValue()
    {
        $this->cache->set('key', new \StdClass());
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
        $this->collection->insertOne(['_id' => 'key1']);
        $this->collection->insertOne(['_id' => 'key2']);

        $this->assertTrue($this->cache->delete('key1'));

        $actual = $this->collection->find(
            [],
            ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
        )->toArray();

        $this->assertEquals([['_id' => 'key2']], $actual);
    }

    /**
     * Verify behavior of delete() when mongo exception is thrown.
     *
     * @test
     * @covers ::delete
     *
     * @return void
     */
    public function deleteMongoException()
    {
        $mockCollection = $this->getMockBuilder(
            '\\MongoDB\\Collection',
            ['deleteOne', 'createIndex']
        )->disableOriginalConstructor()->getMock();
        $mockCollection->method('deleteOne')->will($this->throwException(new \Exception()));
        $cache = new MongoCache($mockCollection);
        $this->assertFalse($cache->delete('key'));
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
        $this->collection->insertOne(['_id' => 'key1']);
        $this->collection->insertOne(['_id' => 'key2']);

        $this->assertTrue($this->cache->clear());

        $actual = $this->collection->find(
            [],
            ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
        )->toArray();

        $this->assertSame([], $actual);
    }

    /**
     * Verify behavior of clear() when mongo exception is thrown.
     *
     * @test
     * @covers ::clear
     *
     * @return void
     */
    public function clearMongoException()
    {
        $mockCollection = $this->getMockBuilder(
            '\\MongoDB\\Collection',
            ['deleteMany', 'createIndex']
        )->disableOriginalConstructor()->getMock();
        $mockCollection->method('deleteMany')->will($this->throwException(new \Exception()));
        $cache = new MongoCache($mockCollection);
        $this->assertFalse($cache->clear());
    }

    /**
     * Verify basic behavior of getMultiple
     *
     * @test
     * @covers ::getMultiple
     *
     * @return void
     */
    public function getMultiple()
    {
        $json = json_encode(['status' => 'ok']);
        $headers = ['Content-Type' => ['application/json'], 'eTag' => ['"an etag"']];
        $this->collection->insertOne(
            [
                '_id' => 'key1',
                'statusCode' => 200,
                'headers' => $headers,
                'body' => $json,
                'protocolVersion' => '1.1',
                'reasonPhrase' => 'OK',
                'expires' => new UTCDateTime(strtotime('+1 day') * 1000),
            ]
        );
        $this->collection->insertOne(
            [
                '_id' => 'key3',
                'statusCode' => 200,
                'headers' => $headers,
                'body' => $json,
                'protocolVersion' => '1.1',
                'reasonPhrase' => 'OK',
                'expires' => new UTCDateTime(strtotime('+1 day') * 1000),
            ]
        );

        $default = new \StdClass();

        $responses = $this->cache->getMultiple(['key1', 'key2', 'key3', 'key4'], $default);

        $this->assertCount(4, $responses);

        $this->assertSame(200, $responses['key1']->getStatusCode());
        $this->assertSame($headers, $responses['key1']->getHeaders());
        $this->assertSame($json, (string)$responses['key1']->getBody());
        $this->assertSame('1.1', $responses['key1']->getProtocolVersion());
        $this->assertSame('OK', $responses['key1']->getReasonPhrase());

        $this->assertSame($default, $responses['key2']);

        $this->assertSame(200, $responses['key3']->getStatusCode());
        $this->assertSame($headers, $responses['key3']->getHeaders());
        $this->assertSame($json, (string)$responses['key3']->getBody());
        $this->assertSame('1.1', $responses['key3']->getProtocolVersion());
        $this->assertSame('OK', $responses['key3']->getReasonPhrase());

        $this->assertSame($default, $responses['key4']);
    }

    /**
     * Verify basic behavior of setMultiple().
     *
     * @test
     * @covers ::setMultiple
     *
     * @return void
     */
    public function setMultple()
    {
        $responses = [
            'key1' => $this->getResponse(),
            'key2' => $this->getResponse(),
        ];

        $this->assertTrue($this->cache->setMultiple($responses, 86400));

        $actual = $this->collection->find(
            [],
            ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
        )->toArray();

        $expires = new UTCDateTime((time() + 86400) * 1000);
        $this->assertResponseDocument('key1', $expires, $responses['key1'], $actual[0]);
        $this->assertResponseDocument('key2', $expires, $responses['key2'], $actual[1]);
    }

    /**
     * Verify behavior of setMultiple() when mongo throws an exception.
     *
     * @test
     * @covers ::setMultiple
     *
     * @return void
     */
    public function setMultpleMongoException()
    {
        $mockCollection = $this->getMockBuilder(
            '\\MongoDB\\Collection',
            ['updateOne', 'createIndex']
        )->disableOriginalConstructor()->getMock();
        $mockCollection->method('updateOne')->will($this->throwException(new \Exception()));
        $cache = new MongoCache($mockCollection);
        $responses = ['key1' => $this->getResponse(), 'key2' => $this->getResponse() ];
        $this->assertFalse($cache->setMultiple($responses, 86400));
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
        $this->collection->insertOne(['_id' => 'key1']);
        $this->collection->insertOne(['_id' => 'key2']);
        $this->collection->insertOne(['_id' => 'key3']);

        $this->assertTrue($this->cache->deleteMultiple(['key1', 'key3']));

        $actual = $this->collection->find(
            [],
            ['typeMap' => ['root' => 'array', 'document' => 'array', 'array' => 'array']]
        )->toArray();

        $this->assertEquals([['_id' => 'key2']], $actual);
    }

    /**
     * Verify behavior of deleteMultiple() when mongo throws an exception.
     *
     * @test
     * @covers ::deleteMultiple
     *
     * @return void
     */
    public function deleteMultipleMongoException()
    {
        $mockCollection = $this->getMockBuilder(
            '\\MongoDB\\Collection',
            ['deleteMany', 'createIndex']
        )->disableOriginalConstructor()->getMock();
        $mockCollection->method('deleteMany')->will($this->throwException(new \Exception()));
        $cache = new MongoCache($mockCollection);
        $this->assertFalse($cache->deleteMultiple(['key1', 'key3']));
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
        $this->collection->insertOne(['_id' => 'key1']);
        $this->assertTrue($this->cache->has('key1'));
        $this->assertFalse($this->cache->has('key2'));
    }

    /**
     * Assert that the given document contains the values of the given Response.
     *
     * @param string            $key      The expected _id of the document.
     * @param UTCDateTime       $expires  The expected expires datetime value.
     * @param ResponseInterface $response The PSR-7 Response.
     * @param array             $document The mongodb document.
     *
     * @return void
     */
    private function assertResponseDocument(
        string $key,
        UTCDateTime $expires,
        ResponseInterface $response,
        array $document
    ) {
        $this->assertEquals(
            [
                '_id' => $key,
                'statusCode' => $response->getStatusCode(),
                'headers' => $response->getHeaders(),
                'body' => (string)$response->getBody(),
                'protocolVersion' => $response->getProtocolVersion(),
                'reasonPhrase' => $response->getReasonPhrase(),
                'expires' => $expires,
            ],
            $document
        );
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
