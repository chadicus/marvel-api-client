<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Chadicus\Marvel\Api\RequestInterface;
use Chadicus\Marvel\Api\Response;

/**
 * Adapter implementation that only returns empty responses.
 */
final class FakeAdapter implements AdapterInterface
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

        return new Response(200, [], []);
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
