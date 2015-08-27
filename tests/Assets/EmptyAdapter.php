<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Chadicus\Marvel\Api\RequestInterface;
use Chadicus\Marvel\Api\Response;

/**
 * Adapter implementation that only returns empty responses.
 */
final class EmptyAdapter implements AdapterInterface
{
    /**
     * Returns an empty Response.
     *
     * @param RequestInterface $request The request to send.
     *
     * @return ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        $this->request = $request;

        return new Response(
            200,
            ['Content-type' => 'application/json', 'etag' => 'an etag'],
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
    }
}
