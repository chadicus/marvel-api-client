<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * Mock handler that only returns empty responses.
 */
final class EmptyHandler
{
    /**
     * Returns an empty Response.
     *
     * @param RequestInterface $request The request to send.
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request)
    {
        $this->request = $request;

        $body = json_encode(
            [
                'code' => 200,
                'status' => 'ok',
                'etag' => 'an etag',
                'data' => [
                    'offset' => 0,
                    'limit' => 20,
                    'total' => 0,
                    'count' => 0,
                    'results' => [],
                ],
            ]
        );

        return new Response(200, ['Content-type' => 'application/json', 'etag' => 'an etag'], $body);
    }
}
