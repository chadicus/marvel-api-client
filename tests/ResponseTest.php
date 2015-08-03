<?php
namespace Chadicus\Marvel\Api;

/**
 * Defines unit tests for the Response class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Response
 */
final class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify basic functionality of the response object.
     *
     * @test
     * @covers ::__construct
     * @covers ::getHttpCode
     * @covers ::getHeaders
     * @covers ::getBody
     *
     * @return void
     */
    public function construct()
    {
        $httpCode = 200;
        $headers = ['Content-Type' => 'text/json'];
        $body = ['doesnt' => 'matter'];
        $response = new Response($httpCode, $headers, $body);
        $this->assertSame($httpCode, $response->getHttpCode());
        $this->assertSame($headers, $response->getHeaders());
        $this->assertSame($body, $response->getBody());
    }

    /**
     * Verify basic behavior of getDataWrapper()
     *
     * @test
     * @covers ::getDataWrapper
     *
     * @return void
     */
    public function getDataWrapper()
    {
        $body = [
            'code' => 200,
            'status' => 'ok',
            'copyright' => 'a copyright',
            'attributionText' => 'a attributionText',
            'attributionHTML' => 'a attributionHTML',
            'etag' => 'a etag',
        ];

        $response = new Response(200, ['Content-Type' => 'application/json'], $body);

        $this->assertSame($body['code'], $response->getDataWrapper()->getCode());
        $this->assertSame($body['status'], $response->getDataWrapper()->getStatus());
        $this->assertSame($body['copyright'], $response->getDataWrapper()->getCopyRight());
        $this->assertSame($body['attributionText'], $response->getDataWrapper()->getAttributionText());
        $this->assertSame($body['attributionHTML'], $response->getDataWrapper()->getAttributionHTML());
        $this->assertSame($body['etag'], $response->getDataWrapper()->getEtag());
    }

    /**
     * Verify invalid constructor parameters cause exceptions.
     *
     * @param integer $httpCode The http response code.
     * @param array   $headers  The response headers.
     * @param array   $body     The response body.
     *
     * @test
     * @covers ::__construct
     * @dataProvider constructorBadData
     * @expectedException \InvalidArgumentException
     *
     * @return void
     */
    public function constructWithInvalidParameters($httpCode, array $headers, array $body)
    {
        new Response($httpCode, $headers, $body);
    }

    /**
     * Data provider for constructWithInvalidParameters.
     *
     * @return array
     */
    public function constructorBadData()
    {
        return [
            'httpCode is not a number' => ['NaN', [], []],
            'httpCode is less than 100' => [99, [], []],
            'httpCode is greater than 600' => [601, [], []],
        ];
    }
}
