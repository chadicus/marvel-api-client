<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;

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
        return new Response('php://memory', 200);
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
