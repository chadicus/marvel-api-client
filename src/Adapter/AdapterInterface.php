<?php

namespace Chadicus\Marvel\Api\Adapter;

use Psr\Http\Message\RequestInterface;

/**
 * Simple interface for a client adapter.
 */
interface AdapterInterface
{
    /**
     * Execute the specified request against the Marvel API.
     *
     * @param RequestInterface $request The request to send.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(RequestInterface $request);
}
