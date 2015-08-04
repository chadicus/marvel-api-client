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
     * @param Request $request The request to send.
     *
     * @return Api\Response
     */
    public function send(Api\Request $request);
}
