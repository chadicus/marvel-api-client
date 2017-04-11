<?php

namespace Chadicus\Marvel\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Zend\Diactoros\Request;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

/**
 * Unit tests for the Client class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Client
 * @covers ::<private>
 */
final class ClientTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Set up each test.
     *
     * @return void
     */
    public function setUp()
    {
        \Chadicus\FunctionRegistry::reset(__NAMESPACE__, ['date']);
    }

    /**
     * Verify basic behavior of search().
     *
     * @test
     * @covers ::__construct
     * @covers ::search
     *
     * @return void
     */
    public function search()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'time',
            function () {
                return 1;
            }
        );

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode([]));
        $guzzleResponse = new Response($stream, 200);
        $mockHandler = new MockHandler([$guzzleResponse]);
        $container = [];
        $history = Middleware::history($container);
        $stack = HandlerStack::create($mockHandler);
        $stack->push($history);
        $guzzleClient = new GuzzleClient(['handler' => $stack]);

        $client = new Client('aPrivateKey', 'aPublicKey', $guzzleClient);
        $client->search('a Resource', ['key' => 'value']);

        $this->assertCount(1, $container);
        $request = $container[0]['request'];

        $hash = md5('1aPrivateKeyaPublicKey');
        $expectedUrl = Client::BASE_URL . "a+Resource?key=value&apikey=aPublicKey&ts=1&hash={$hash}";

        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($expectedUrl, (string)$request->getUri());
    }

    /**
     * Verify behavior of search when API returns non 200 response.
     *
     * @test
     * @covers ::search
     *
     * @return void
     */
    public function searchNot200Reponse()
    {
        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode([]));
        $guzzleResponse = new Response($stream, 503);
        $mockHandler = new MockHandler([$guzzleResponse]);
        $container = [];
        $history = Middleware::history($container);
        $stack = HandlerStack::create($mockHandler);
        $stack->push($history);
        $guzzleClient = new GuzzleClient(['handler' => $stack]);
        $client = new Client('aPrivateKey', 'aPublicKey', $guzzleClient);
        $this->assertNull($client->search('a Resource', ['key' => 'value']));
    }

    /**
     * Verify basic behavior of get().
     *
     * @test
     * @covers ::__construct
     * @covers ::get
     *
     * @return void
     */
    public function get()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'time',
            function () {
                return 1;
            }
        );

        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode([]));

        $guzzleResponse = new Response($stream, 200);
        $mockHandler = new MockHandler([$guzzleResponse]);
        $container = [];
        $history = Middleware::history($container);
        $stack = HandlerStack::create($mockHandler);
        $stack->push($history);
        $guzzleClient = new GuzzleClient(['handler' => $stack]);

        $client = new Client('aPrivateKey', 'aPublicKey', $guzzleClient);
        $client->get('a Resource', 1);

        $this->assertCount(1, $container);
        $request = $container[0]['request'];
        $hash = md5('1aPrivateKeyaPublicKey');
        $expectedUrl = Client::BASE_URL . "a+Resource/1?apikey=aPublicKey&ts=1&hash={$hash}";

        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($expectedUrl, (string)$request->getUri());
    }

    /**
     * Verify behavior of get() when API returns non 200 response.
     *
     * @test
     * @covers ::get
     *
     * @return void
     */
    public function getNot200Reponse()
    {
        $stream = new Stream('php://memory', 'r+');
        $stream->write(json_encode([]));
        $guzzleResponse = new Response($stream, 503);
        $mockHandler = new MockHandler([$guzzleResponse]);
        $container = [];
        $history = Middleware::history($container);
        $stack = HandlerStack::create($mockHandler);
        $stack->push($history);
        $guzzleClient = new GuzzleClient(['handler' => $stack]);
        $client = new Client('aPrivateKey', 'aPublicKey', $guzzleClient);
        $this->assertNull($client->get('a Resource', 1));
    }

    /**
     * Verfiy response is return from cache.
     *
     * @test
     * @covers ::get
     *
     * @return void
     */
    public function getFromCache()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'time',
            function () {
                return 1;
            }
        );

        $stream = new Stream('php://temp', 'r+');
        $stream->write(
            json_encode(
                [
                   'code' => 200,
                   'status' => 'OK',
                   'attributionText' => 'a attributionText',
                   'attributionHTML' => 'a attributionHTML',
                   'etag' => 'a etag',
                   'data' =>  [
                       'offset' => 0,
                       'limit' => 1,
                       'total' => 1,
                       'count' => 1,
                       'results' => [['id' => 1, 'resourceURI' => Client::BASE_URL . 'characters/1']],
                   ],
                ]
            )
        );

        $hash = md5('1aPrivateKeyaPublicKey');
        $cache = new Assets\ArrayCache();
        $cache->set(
            Client::BASE_URL . "a+Resource/1?apikey=aPublicKey&ts=1&hash={$hash}",
            new Response($stream, 200, ['custom' => 'header'])
        );

        $guzzleResponse = new Response('php://memory', 200);
        $mockHandler = new MockHandler([$guzzleResponse]);
        $container = [];
        $history = Middleware::history($container);
        $stack = HandlerStack::create($mockHandler);
        $stack->push($history);
        $guzzleClient = new GuzzleClient(['handler' => $stack]);

        $client = new Client('aPrivateKey', 'aPublicKey', $guzzleClient, $cache);
        $client->get('a Resource', 1);

        $dataWrapper = $client->get('a Resource', 1);
        $this->assertSame(200, $dataWrapper->getCode());
        $this->assertSame('OK', $dataWrapper->getStatus());
        $this->assertSame(1, $dataWrapper->getData()->getCount());

        // assert the adapter was not used
        $this->assertCount(0, $container);
    }

    /**
     * Verfiy response is return from cache.
     *
     * @test
     * @covers ::get
     *
     * @return void
     */
    public function getSetsCache()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'time',
            function () {
                return 1;
            }
        );

        $hash = md5('1aPrivateKeyaPublicKey');
        $request = new Request(Client::BASE_URL . "a+Resource/1?apikey=aPublicKey&ts=1&hash={$hash}", 'GET');

        $cache = new Assets\ArrayCache();

        $handler = new Assets\FakeHandler();
        $guzzleClient = new GuzzleClient(['handler' => $handler]);

        $client = new Client('aPrivateKey', 'aPublicKey', $guzzleClient, $cache);
        $client->get('a Resource', 1);

        $cachedResponse = $cache->get((string)$request->getUri());
        $this->assertSame($handler->response->getStatusCode(), $cachedResponse->getStatusCode());
        $this->assertSame($handler->response->getHeaders(), $cachedResponse->getHeaders());
        $this->assertSame((string)$handler->response->getBody(), (string)$cachedResponse->getBody());
    }

    /**
     * Verify bahvior of __call() for single entity.
     *
     * @test
     * @covers ::__call
     *
     * @return void
     */
    public function callEntity()
    {
        $guzzleClient = new GuzzleClient(['handler' => new Assets\ComicHandler()]);
        $client = new Client('not under test', 'not under test', $guzzleClient);
        $comic = $client->comics(2);
        $this->assertInstanceOf('Chadicus\Marvel\Api\Entities\Comic', $comic);
        $this->assertSame(2, $comic->getId());
    }

    /**
     * Verify bahvior of __call() for entity that is not found.
     *
     * @test
     * @covers ::__call
     *
     * @return void
     */
    public function callEntityNotFound()
    {
        $guzzleClient = new GuzzleClient(['handler' => new Assets\EmptyHandler()]);
        $client = new Client('not under test', 'not under test', $guzzleClient);
        $comic = $client->comics(1);
        $this->assertNull($comic);
    }

    /**
     * Verify bahvior of __call() for entity that is invalid.
     *
     * @test
     * @covers ::__call
     *
     * @return void
     */
    public function callInvalidEntity()
    {
        $guzzleClient = new GuzzleClient(['handler' => new Assets\ErrorHandler()]);
        $client = new Client('not under test', 'not under test', $guzzleClient);
        $result = $client->batman(1);
        $this->assertNull($result);
    }

    /**
     * Verify basic bahvior of __call() for entity collection.
     *
     * @test
     * @covers ::__call
     *
     * @return void
     */
    public function callCollection()
    {
        $guzzleClient = new GuzzleClient(['handler' => new Assets\ComicHandler()]);
        $client = new Client('not under test', 'not under test', $guzzleClient);
        $comics = $client->comics();
        $this->assertInstanceOf('Chadicus\Marvel\Api\Collection', $comics);
        $this->assertSame(5, $comics->count());
        foreach ($comics as $key => $comic) {
            $this->assertSame($key + 1, $comic->getId());
        }
    }
}
