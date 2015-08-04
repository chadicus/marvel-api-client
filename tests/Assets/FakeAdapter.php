<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use Chadicus\Marvel\Api\Request;
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
     * @param Request $request The request to send.
     *
     * @return Response
     */
    public function send(Request $request)
    {
        $this->request = $request;

        return new Response(200, [], []);
    }

    /**
     * Return the last request given to this Adapter.
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
