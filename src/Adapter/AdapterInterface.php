<?php

namespace Chadicus\Marvel\Api\Adapter;

use Chadicus\Marvel\Api;

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
     * @return Api\ResponseInterface
     */
    public function send(Api\RequestInterface $request);
}
