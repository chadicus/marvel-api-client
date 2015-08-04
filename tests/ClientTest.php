<?php

namespace Chadicus\Marvel\Api;

/**
 * Unit tests for the Client class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Client
 * @covers ::<private>
 */
final class ClientTest extends \PHPUnit_Framework_TestCase
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

        $adapter = new FakeAdapter();
        $client = new Client('aPrivateKey', 'aPublicKey', $adapter);
        $client->search('a Resource', ['key' => 'value']);
        $request = $adapter->getRequest();
        $hash = md5('1aPrivateKeyaPublicKey');
        $expectedUrl = Client::BASE_URL . "a+Resource?key=value&apikey=aPublicKey&ts=1&hash={$hash}";

        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($expectedUrl, $request->getUrl());

    }

    /**
     * Verify proper exceptions thrown when Client is constructed with bad data.
     *
     * @param string  $privateApiKey The private api key issued by Marvel.
     * @param string  $publicApiKey  The public api key issued by Marvel.
     * @param Adapter $adapter       Implementation of a client adapter.
     *
     * @test
     * @covers ::__construct
     * @dataProvider badConstructorData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function constructWithBadData($privateApiKey, $publicApiKey, Adapter $adapter)
    {
        new Client($privateApiKey, $publicApiKey, $adapter);
    }

    /**
     * Data adapter for constructWithBadData test.
     *
     * @return array
     */
    public function badConstructorData()
    {
        return [
            // privateApiKey
            'privateApiKey is null' => [null, 'a public key', new FakeAdapter()],
            'privateApiKey is empty' => ['', 'a public key', new FakeAdapter()],
            'privateApiKey is whitespace' => [" \n\t", 'a public key', new FakeAdapter()],
            'privateApiKey is not a string' => [true, 'a public key', new FakeAdapter()],
            // publicApiKey
            'publicApiKey is null' => ['a private key', null, new FakeAdapter()],
            'publicApiKey is empty' => ['a private key', '', new FakeAdapter()],
            'publicApiKey is whitespace' => ['a private key', "\n \t", new FakeAdapter()],
            'publicApiKey is not a string' => ['a private key', false, new FakeAdapter()],
        ];
    }

    /**
     * Verify proper exceptions thrown when Client is constructed with bad data.
     *
     * @param string $resource The API resource to search for.
     * @param array  $filters  Array of search criteria to use in request.
     *
     * @test
     * @covers ::search
     * @dataProvider badSearchData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function searchtWithBadData($resource, array $filters)
    {
        (new Client('not under test', 'not under test', new FakeAdapter()))->search($resource, $filters);
    }

    /**
     * Data adapter for searchWithBadData test.
     *
     * @return array
     */
    public function badSearchData()
    {
        return [
            // resource
            'resource is null' => [null, []],
            'resource is empty' => ['', []],
            'resource is whitespace' => [" \n\t", []],
            'resource is not a string' => [true, []],
        ];
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

        $adapter = new FakeAdapter();
        $client = new Client('aPrivateKey', 'aPublicKey', $adapter);
        $client->get('a Resource', 1);
        $request = $adapter->getRequest();
        $hash = md5('1aPrivateKeyaPublicKey');
        $expectedUrl = Client::BASE_URL . "a+Resource/1?apikey=aPublicKey&ts=1&hash={$hash}";

        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($expectedUrl, $request->getUrl());

    }

    /**
     * Verify proper exceptions thrown when Client is constructed with bad data.
     *
     * @param string  $resource The API resource to search for.
     * @param integer $id       The id of the API resource.
     *
     * @test
     * @covers ::get
     * @dataProvider badGetData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function gettWithBadData($resource, $id)
    {
        (new Client('not under test', 'not under test', new FakeAdapter()))->get($resource, $id);
    }

    /**
     * Data adapter for getWithBadData test.
     *
     * @return array
     */
    public function badGetData()
    {
        return [
            // resource
            'resource is null' => [null, 1],
            'resource is empty' => ['',1],
            'resource is whitespace' => [" \n\t",1],
            'resource is not a string' => [true,1],
            // id
            'id is null' => ['a resource', null],
            'id is not an integer' => ['a resource', true],
        ];
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

        $hash = md5('1aPrivateKeyaPublicKey');
        $cache = new Cache\ArrayCache();
        $cache->set(
            new Request(Client::BASE_URL . "a+Resource/1?apikey=aPublicKey&ts=1&hash={$hash}", 'GET'),
            new Response(599, ['custom' => 'header'], ['key' => 'value'])
        );
        $adapter = new FakeAdapter();
        $client = new Client('aPrivateKey', 'aPublicKey', $adapter, $cache);
        $response = $client->get('a Resource', 1);
        $this->assertSame(599, $response->getHttpCode());
        $this->assertSame(['custom' => 'header'], $response->getHeaders());
        $this->assertSame(['key' => 'value'], $response->getBody());

        // assert the adapter was not used
        $this->assertNull($adapter->getRequest());
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

        $cache = new Cache\ArrayCache();
        $adapter = new FakeAdapter();
        $client = new Client('aPrivateKey', 'aPublicKey', $adapter, $cache);
        $response = $client->get('a Resource', 1);

        $cachedResponse = $cache->get($request);
        $this->assertSame($response->getHttpCode(), $cachedResponse->getHttpCode());
        $this->assertSame($response->getHeaders(), $cachedResponse->getHeaders());
        $this->assertSame($response->getBody(), $cachedResponse->getBody());
    }
}
