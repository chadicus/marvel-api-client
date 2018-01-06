<?php

namespace Chadicus\Marvel\Api\Cache;

use Chadicus\Psr\SimpleCache\Serializer\SerializerInterface;
use DominionEnterprises\Util\Arrays;
use GuzzleHttp\Psr7\Response;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Provides serialization from arrays to PSR-7 response objects.
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
    public function unserialize($data) //@codingStandardsIgnoreLine Interface does not define type-hints or return
    {
        $this->ensure(is_array($data), '$data was not an array');
        return new Response(
            Arrays::get($data, 'statusCode'),
            Arrays::get($data, 'headers'),
            Arrays::get($data, 'body'),
            Arrays::get($data, 'protocolVersion'),
            Arrays::get($data, 'reasonPhrase')
        );
    }

    /**
     * Serializes the given data for storage in caching.
     *
     * @param mixed $value The data to serialize for caching.
     *
     * @return array The result of serializing the given $data.
     *
     * @throws InvalidArgumentException Thrown if the given value is not a PSR-7 Response instance.
     */
    public function serialize($value)
    {
        $this->ensure(
            is_a($value, '\\Psr\\Http\\Message\\ResponseInterface'),
            '$value was not a PSR-7 Response'
        );

        return [
            'statusCode' => $value->getStatusCode(),
            'headers' => $value->getHeaders(),
            'body' => (string)$value->getBody(),
            'protocolVersion' => $value->getProtocolVersion(),
            'reasonPhrase' => $value->getReasonPhrase(),
        ];
    }

    private function ensure(bool $condition, string $message)
    {
        if ($condition) {
            return;
        }

        throw new class($message) extends \Exception implements InvalidArgumentException
        {
        };
    }
}
