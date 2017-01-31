<?php

namespace Chadicus\Marvel\Api\Adapter;

use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Stream;
use DominionEnterprises\Util;
use DominionEnterprises\Util\Arrays;
use DominionEnterprises\Util\Http;

/**
 * Concrete implementation of Adapter using cURL.
 */
final class CurlAdapter implements AdapterInterface
{
    /**
     * Execute the specified request against the Marvel API.
     *
     * @param RequestInterface $request The request to send.
     *
     * @return Psr\Http\Message\ResponseInterface
     *
     * @throws \Exception Throws on error.
     */
    public function send(RequestInterface $request)
    {
        $curlHeaders = ['Expect:'];//stops curl automatically putting in expect 100.
        foreach ($request->getHeaders() as $key => $value) {
            $curlHeaders[] = "{$key}: {$request->getHeaderLine($key)}";
        }

        $curlOptions = [
            CURLOPT_URL => (string)$request->getUri(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => false,
            CURLOPT_HEADER => true,
            CURLOPT_FORBID_REUSE => true,
            CURLOPT_HTTPHEADER => $curlHeaders,
            CURLOPT_ENCODING => 'gzip,deflate',
        ];

        if (strtoupper($request->getMethod()) !== 'GET') {
            throw new \Exception("Unsupported method '{$request->getMethod()}' given");
        }

        $curl = Util::ensureNot(false, curl_init(), 'Unable to initialize connection');

        Util::ensureNot(false, curl_setopt_array($curl, $curlOptions), 'Unable to prepare connection');

        $result = Util::ensureNot(false, curl_exec($curl), curl_error($curl));

        $headerSize = Util::ensureNot(
            false,
            curl_getinfo($curl, CURLINFO_HEADER_SIZE),
            'Unable to determine header size'
        );

        $httpCode = Util::ensureNot(
            false,
            curl_getinfo($curl, CURLINFO_HTTP_CODE),
            'Unable to determine response HTTP code'
        );

        $headers = Http::parseHeaders(substr($result, 0, $headerSize - 1));
        unset($headers['Response Code']);
        unset($headers['Response Status']);

        $body = trim(substr($result, $headerSize));
        $stream = new Stream('php://temp', 'r+');
        $stream->write($body);
        return new Response($stream, $httpCode, $headers);
    }
}
