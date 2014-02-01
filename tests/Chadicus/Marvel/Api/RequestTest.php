<?php
namespace Chadicus\Marvel\Api;

/**
 * Unit tests for the Request class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Request
 */
final class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic functionality of the request object.
     *
     * @test
     * @covers ::__construct
     * @covers ::getUrl
     * @covers ::getBody
     * @covers ::getMethod
     * @covers ::getHeaders
     *
     * @return void
     */
    public function construct()
    {
        $url = 'a url';
        $method = 'a method';
        $body = ['some' => 'data'];
        $headers = ['key' => 'value'];
        $request = new Request($url, $method, $headers, $body);
        $this->assertSame($url, $request->getUrl());
        $this->assertSame($method, $request->getMethod());
        $this->assertSame($headers, $request->getHeaders());
        $this->assertSame($body, $request->getBody());
    }

    /**
     * Verify invalid constructor parameters cause exceptions.
     *
     * @param mixed $url     The url of the request.
     * @param mixed $method  The http method of the request.
     * @param array $headers The headers of the request.
     * @param array $body    The body of the request.
     *
     * @test
     * @covers ::__construct
     * @dataProvider badData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function constructWithInvalidParameters($url, $method, array $headers = [], array $body = [])
    {
        new Request($url, $method, $headers, $body);
    }

    /**
     * Provides data for __construct test.
     *
     * @return array
     */
    public function badData()
    {
        return [
            // url checks
            'url is null' => [null, 'method', [], []],
            'url is whitespace' => [" \n ", 'method', [], []],
            'url is empty' => ['', 'method', [], []],
            'url is not a string' => [1, 'method', [], []],
            // method checks
            'method is null' => ['url', null, [], []],
            'method is whitespace' => ['url', " \n ", [], []],
            'method is empty' => ['url', '', [], []],
            'method is not a string' => ['url', 1, [], []],
        ];
    }
}
