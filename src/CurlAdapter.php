<?php

namespace Chadicus\Marvel\Api;

use DominionEnterprises\Util;
use DominionEnterprises\Util\Arrays;
use DominionEnterprises\Util\Http;

/**
 * Concrete implementation of Adapter using cURL.
 */
final class CurlAdapter implements Adapter
{
    /**
     * Execute the specified request against the Marvel API.
     *
     * @param Request $request The request to send.
     *
     * @return Response
     *
     * @throws \Exception Throws on error.
     */
    public function send(Request $request)
    {
        $curlHeaders = array('Expect:');//stops curl automatically putting in expect 100.
        foreach ($request->getHeaders() as $key => $value) {
            $curlHeaders[] = "{$key}: {$value}";
        }

        $curlOptions = array(
            CURLOPT_URL => $request->getUrl(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_VERBOSE => false,
            CURLOPT_HEADER => true,
            CURLOPT_FORBID_REUSE => true,
            CURLOPT_HTTPHEADER => $curlHeaders,
            CURLOPT_ENCODING => 'gzip,deflate',
        );

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
        $body = json_decode(substr($result, $headerSize), true);
        $error = Arrays::get(
            [
                JSON_ERROR_DEPTH => 'The maximum stack depth has been exceeded',
                JSON_ERROR_STATE_MISMATCH => ' Invalid or malformed JSON',
                JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
                JSON_ERROR_SYNTAX => 'Syntax error',
                JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded',
            ],
            json_last_error()
        );

        Util::ensure(null, $error, "Unable to parse response: {$error}");

        return new Response($httpCode, $headers, $body ?: []);
    }
}
