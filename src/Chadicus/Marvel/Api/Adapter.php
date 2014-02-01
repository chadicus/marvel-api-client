<?php
/**
 * Contains the Chadicus\Marvel\Api\Adapter interface
 */
namespace Chadicus\Marvel\Api;

/**
 * Simple interface for a client adapter.
 */
interface Adapter
{
    /**
     * Execute the specified request against the Marvel API.
     *
     * @param Request $request The request to send.
     *
     * @return Response
     */
    public function send(Request $request);
}
