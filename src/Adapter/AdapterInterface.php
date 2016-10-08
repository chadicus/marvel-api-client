<?php

namespace Chadicus\Marvel\Api\Adapter;

use Chadicus\Marvel\Api\RequestInterface;

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
     * @return \Chadicus\Marvel\Api\ResponseInterface
     */
    public function send(RequestInterface $request);
}
