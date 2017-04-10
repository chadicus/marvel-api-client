<?php

namespace Chadicus\Marvel\Api\Cache;

use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

/**
 * @coversDefaultClass \Chadicus\Marvel\Api\Cache\Serializer
 * @covers ::<private>
 */
final class SerializerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Verify basic use case of unserialize().
     *
     * @test
     * @covers ::unserialize
     *
     * @return void
     */
    public function unserialize()
    {
        $json = json_encode(['status' => 'ok']);
        $headers = ['Content-Type' => ['application/json'], 'eTag' => ['"an etag"']];
        $data = [
            'statusCode' => 200,
            'headers' => $headers,
            'body' => $json,
            'protocolVersion' => '1.1',
            'reasonPhrase' => 'OK',
        ];
        $serializer = new Serializer();
        $response = $serializer->unserialize($data);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame($headers, $response->getHeaders());
        $this->assertSame($json, (string)$response->getBody());
        $this->assertSame('1.1', $response->getProtocolVersion());
        $this->assertSame('OK', $response->getReasonPhrase());

    }

    /**
     * Verify basic use case of serialize().
     *
     * @test
     * @covers ::serialize
     *
     * @return void
     */
    public function serialize()
    {
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, json_encode(['status' => 'ok']));
        $response = new Response(
            new Stream($stream),
            200,
            ['Content-Type' => 'application/json', 'eTag' => '"an etag"']
        );
        $serializer = new Serializer();
        $this->assertSame(
            [
                'statusCode' => 200,
                'headers' => ['Content-Type' => ['application/json'], 'eTag' => ['"an etag"']],
                'body' => json_encode(['status' => 'ok']),
                'protocolVersion' => '1.1',
                'reasonPhrase' => 'OK',
            ],
            $serializer->serialize($response)
        );
    }
}
