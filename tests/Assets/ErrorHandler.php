<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * Mock Handler that only returns 404 responses.
 */
final class ErrorHandler
{
    /**
     * The last request given to this adapter.
     *
     * @var Request
     */
    private $request = null;

    /**
     * Returns a 404 Response.
     *
     * @param RequestInterface $request The request to send.
     *
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request)
    {
        $this->request = $request;
        $body =json_encode(
            [
                'code' => 'ResourceNotFound',
                'message' => "{$request->getUri()} was not found",
            ]
        );

        return new Response(404, ['Content-Type' => 'application/json'], $body);
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
