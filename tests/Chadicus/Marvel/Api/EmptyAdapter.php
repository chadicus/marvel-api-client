<?php
namespace Chadicus\Marvel\Api;

/**
 * Adapter implementation that only returns empty responses.
 */
final class EmptyAdapter implements Adapter
{
    /**
     * Returns an empty Response.
     *
     * @param Request $request The request to send.
     *
     * @return Response
     */
    public function send(Request $request)
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
