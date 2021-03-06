<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Client;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * Mock handler that only returns a responses with one item.
 */
final class SingleHandler
{
    /**
     * The last HTTP request sent to the handler.
     *
     * @var RequestInterface
     */
    public $request;

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

        return new Response(200, ['Content-type' => 'application/json', 'etag' => 'an etag'], $body);
    }
}
