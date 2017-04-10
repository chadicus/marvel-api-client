<?php

namespace Chadicus\Marvel\Api\Cache;

use GuzzleHttp\Psr7\Response;
use Chadicus\Psr\SimpleCache\SerializerInterface;
use DominionEnterprises\Util;

/**
 * Provides serialization from mongo documents to PSR-7 response objects.
 */
final class Serializer implements SerializerInterface
{
    /**
     * Unserializes cached data into the original state.
     *
     * @param array $data The data to unserialize.
     *
     * @return Response
     */
    public function unserialize(array $data)
    {
        return new Response(
            $data['statusCode'],
            $data['headers'],
            $data['body'],
            $data['protocolVersion'],
            $data['reasonPhrase']
        );
    }

    /**
     * Serializes the given data for storage in caching.
     *
     * @param mixed $value The data to serialize for caching.
     *
     * @return array The result of serializing the given $data.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException Thrown if the given value is not a PSR-7 Response instance.
     */
    public function serialize($value) : array
    {
        Util::ensure(
            true,
            is_a($value, '\\Psr\\Http\\Message\\ResponseInterface'),
            '\\Chadicus\\Psr\\SimpleCache\\InvalidArgumentException',
            ['$value must be an instance of \\Psr\\Http\\Message\\ResponseInterface']
        );

        return [
            'statusCode' => $value->getStatusCode(),
            'headers' => $value->getHeaders(),
            'body' => (string)$value->getBody(),
            'protocolVersion' => $value->getProtocolVersion(),
            'reasonPhrase' => $value->getReasonPhrase(),
        ];
    }
}
