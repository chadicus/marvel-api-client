<?php
namespace Chadicus\Marvel\Api;

/**
 * Adapter implementation that only returns a responses with one item.
 */
final class SingleAdapter implements Adapter
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
                    'total' => 1,
                    'count' => 1,
                    'results' => [
                        [
                            'id' => 0,
                            'title' => 'a title for comic 0',
                            'resourceURI' => Client::BASE_URL . 'comics/0',
                        ],
                    ],
                ],
            ]
        );
    }
}
