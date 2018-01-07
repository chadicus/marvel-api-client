<?php

namespace Chadicus\Marvel\Api\Cache;

use GuzzleHttp\Psr7\Response;

/**
 * @coversDefaultClass Chadicus\Marvel\Api\Cache\Serializer
 * @covers ::<private>
 */
final class SerializerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Serializer instance to use in tests.
     *
     * @var Serializer
     */
    private $serializer;

    /**
     * Prepare each test
     *
     * @return void
     */
    public function setUp()
    {
        $this->serializer = new Serializer();
    }

    /**
     * @test
     * @covers ::unserialize
     *
     * @return void
     */
    public function unserialize()
    {
        $response = $this->serializer->unserialize(
            [
                'statusCode' => 200,
                'headers' => ['Content-type' => 'application/json', 'etag' => '"an etag"'],
                'body' => json_encode(['status' => 'ok']),
                'protocolVersion' => '1.1',
                'reasonPhrase' => 'OK',
            ]
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(['Content-type' => ['application/json'], 'etag' => ['"an etag"']], $response->getHeaders());
        $this->assertSame(json_encode(['status' => 'ok']), (string)$response->getBody());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame('OK', $response->getReasonPhrase());
    }

    /**
     * @test
     * @covers ::unserialize
     * @expectedException \Psr\SimpleCache\InvalidArgumentException
     *
     * @return void
     */
    public function unserializeNotArray()
    {
        $this->serializer->unserialize('not an array');
    }

    /**
     * @test
     * @covers ::serialize
     *
     * @return void
     */
    public function serialize()
    {
        $this->assertSame(
            [
                'statusCode' => 200,
                'headers' => ['Content-type' => ['application/json'], 'etag' => ['"an etag"']],
                'body' => json_encode(['status' => 'ok']),
                'protocolVersion' => '1.1',
                'reasonPhrase' => 'OK',
            ],
            $this->serializer->serialize($this->getResponse())
        );
    }

    /**
     * @test
     * @covers ::serialize
     * @expectedException \Psr\SimpleCache\InvalidArgumentException
     *
     * @return void
     */
    public function serializeNotResponse()
    {
        $this->serializer->serialize('not a Response object');
    }

    private function getResponse() : Response
    {
        return new Response(
            200,
            ['Content-type' => 'application/json', 'etag' => '"an etag"'],
            json_encode(['status' => 'ok'])
        );
    }
}
