<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;

/**
 * Adapter implementation that only returns empty responses.
 */
final class ErrorAdapter implements AdapterInterface
{
    /**
     * The last request given to this adapter.
     *
     * @var Request
     */
    private $request = null;

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
                    'code' => 'ResourceNotFound',
                    'message' => "{$request->getUri()} was not found",
                ]
            )
        );

        return new Response(
            new Stream($stream),
            404,
            [
                'Content-Type' => 'application/json',
            ]
        );
    }

    /**
     * Return the last request given to this Adapter.
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
