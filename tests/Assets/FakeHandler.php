<?php
namespace Chadicus\Marvel\Api\Assets;

use Chadicus\Marvel\Api\Adapter\AdapterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

/**
 * Mock Handler that returns a fake response.
 */
final class FakeHandler
{
    /**
     * The last request given to this handler.
     *
     * @var Request
     */
    public $request = null;

    /**
     * The last response sent by the handler.
     *
     * @var Response
     */
    public $response = null;

    /**
     * The results to be sent in the response
     *
     * @var array
     */
    private $results = [];

    /**
     * Construct a new instance.
     *
     * @param array $results The results to return in the response.
     */
    public function __construct(array $results = [])
    {
        $this->results = $results;
    }

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
        $body = json_encode(
            [
                'code' => 200,
                'status' => 'ok',
                'etag' => 'an etag',
                'data' => [
                    'offset' => 0,
                    'limit' => count($this->results),
                    'total' => count($this->results),
                    'count' => count($this->results),
                    'results' => $this->results,
                ],
            ]
        );

        $this->response = new Response(200, ['Content-Type' => 'application/json'], $body);
        return $this->response;
    }
}
