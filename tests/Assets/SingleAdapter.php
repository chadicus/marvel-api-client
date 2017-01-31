<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Chadicus\Marvel\Api\Client;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

/**
 * Adapter implementation that only returns a responses with one item.
 */
final class SingleAdapter implements AdapterInterface
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

        $stream = fopen('php://temp', 'r+');
        fwrite(
            $stream,
            json_encode(
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
            )
        );
        return new Response(
            new Stream($stream),
            200,
            ['Content-type' => 'application/json', 'etag' => 'an etag']
        );
    }
}
