<?php
namespace Chadicus\Marvel\Api\Adapter;

use Chadicus\Marvel\Api\Request;

/**
 * Unit tests for \Chadicus\Marvel\Api\Adapter\CurlAdapter class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\Adapter\CurlAdapter
 * @covers ::<private>
 */
final class CurlAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * set up each test.
     *
     * @return void
     */
    public function setUp()
    {
        \Chadicus\FunctionRegistry::reset(__NAMESPACE__, ['curl']);
    }

    /**
     * Verify basic behavior of send.
     *
     * @test
     * @covers ::send
     *
     * @return void
     */
    public function send()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_init',
            function () {
                return true;
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_setopt_array',
            function ($curl, array $options) {
                return true;
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_exec',
            function ($curl) {
                return "HTTP/1.1 200 OK\r\nContent-Length: 13\r\nContent-Type: application/json\r\n\n{\"foo\":\"bar\"}";
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_error',
            function ($curl) {
                return '';
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_getinfo',
            function ($curl, $option) {
                if ($option === CURLINFO_HEADER_SIZE) {
                    return 69;
                }

                if ($option === CURLINFO_HTTP_CODE) {
                    return 200;
                }
            }
        );

        $response = (new CurlAdapter())->send(new Request('not under test', 'get', [], ['foo' => 'bar']));
        $this->assertSame(200, $response->getHttpCode());
        $this->assertSame(
            [
                'Response Code' => 200,
                'Response Status' => 'OK',
                'Content-Length' => '13',
                'Content-Type' => 'application/json',
            ],
            $response->getHeaders()
        );
        $this->assertSame(['foo' => 'bar'], $response->getBody());
    }

    /**
     * Verify basic behavior of send.
     *
     * @test
     * @covers ::send
     *
     * @return void
     */
    public function sendSetRequestHeaders()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_init',
            function () {
                return true;
            }
        );

        $actualHeaders = [];

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_setopt_array',
            function ($curl, array $options) use (&$actualHeaders) {
                $actualHeaders = $options[CURLOPT_HTTPHEADER];
                return true;
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_exec',
            function ($curl) {
                return "HTTP/1.1 200 OK\r\nContent-Length: 4\r\nContent-Type: application/json\r\n\n[]";
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_error',
            function ($curl) {
                return '';
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_getinfo',
            function ($curl, $option) {
                if ($option === CURLINFO_HEADER_SIZE) {
                    return 69;
                }

                if ($option === CURLINFO_HTTP_CODE) {
                    return 200;
                }
            }
        );

        (new CurlAdapter())->send(new Request('not under test', 'get', ['foo' => 'bar'], []));

        $this->assertSame(['Expect:', 'foo: bar'], $actualHeaders);
    }

    /**
     * Verify Exception is thrown when $method is not valid.
     *
     * @test
     * @covers ::send
     * @expectedException \Exception
     * @expectedExceptionMessage Unsupported method 'foo' given
     *
     * @return void
     */
    public function sendWithInvalidMethod()
    {
        (new CurlAdapter())->send(new Request('not under test', 'foo', [], []));
    }

    /**
     * Verify Exception is thrown when curl_init fails.
     *
     * @test
     * @covers ::send
     * @expectedException \Exception
     * @expectedExceptionMessage Unable to initialize connection
     *
     * @return void
     */
    public function sendCurlInitFails()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_init',
            function () {
                return false;
            }
        );

        (new CurlAdapter())->send(new Request('not under test', 'get', [], []));
    }

    /**
     * Verify Exception is thrown when curl_setopt_array fails.
     *
     * @test
     * @covers ::send
     * @expectedException \Exception
     * @expectedExceptionMessage Unable to prepare connection
     *
     * @return void
     */
    public function sendCurlSetoptArrayFails()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_setopt_array',
            function () {
                return false;
            }
        );

        (new CurlAdapter())->send(new Request('not under test', 'get', [], []));
    }

    /**
     * Verify Exception is thrown when curl_exec fails.
     *
     * @test
     * @covers ::send
     * @expectedException \Exception
     * @expectedExceptionMessage the error
     *
     * @return void
     */
    public function sendCurlExecFails()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_exec',
            function () {
                return false;
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_error',
            function () {
                return 'the error';
            }
        );

        (new CurlAdapter())->send(new Request('not under test', 'get', [], []));
    }

    /**
     * Verify behavior when curl_getinfo return false for CURLINFO_HEADER_SIZE.
     *
     * @test
     * @covers ::send
     * @expectedException \Exception
     * @expectedExceptionMessage Unable to determine header size
     *
     * @return void
     */
    public function sendCurlGetinfoFailsOnHeaderSize()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_init',
            function () {
                return true;
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_setopt_array',
            function ($curl, array $options) {
                return true;
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_exec',
            function ($curl) {
                return "HTTP/1.1 200 OK\r\nContent-Length: 2\r\nContent-Type: application/json\r\n\n[]";
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_error',
            function ($curl) {
                return '';
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_getinfo',
            function ($curl, $option) {
                if ($option === CURLINFO_HEADER_SIZE) {
                    return false;
                }
            }
        );

        (new CurlAdapter())->send(new Request('not under test', 'get', [], []));
    }

    /**
     * Verify behavior when curl_getinfo return false for CURLINFO_HTTP_CODE.
     *
     * @test
     * @covers ::send
     * @expectedException \Exception
     * @expectedExceptionMessage Unable to determine response HTTP code
     *
     * @return void
     */
    public function sendCurlGetinfoFailsOnHttpCode()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_init',
            function () {
                return true;
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_setopt_array',
            function ($curl, array $options) {
                return true;
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_exec',
            function ($curl) {
                return "HTTP/1.1 200 OK\r\nContent-Length: 4\r\nContent-Type: application/json\r\n\n[]";
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_error',
            function ($curl) {
                return '';
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_getinfo',
            function ($curl, $option) {
                if ($option === CURLINFO_HEADER_SIZE) {
                    return 69;
                }

                if ($option === CURLINFO_HTTP_CODE) {
                    return false;
                }
            }
        );

        (new CurlAdapter())->send(new Request('not under test', 'get', [], []));
    }

    /**
     * Verify behavior when json_last_error returns a value other than JSON_ERROR_NONE.
     *
     * @test
     * @covers ::send
     * @expectedException \Exception
     * @expectedExceptionMessage Unable to parse response: Syntax error
     *
     * @return void
     */
    public function sendInvalidJsonInResult()
    {
        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_init',
            function () {
                return true;
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_setopt_array',
            function ($curl, array $options) {
                return true;
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_exec',
            function ($curl) {
                // contains syntax error
                return "HTTP/1.1 200 OK\r\nContent-Length: 4\r\nContent-Type: application/json\r\n\n{xx}}";
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_error',
            function ($curl) {
                return '';
            }
        );

        \Chadicus\FunctionRegistry::set(
            __NAMESPACE__,
            'curl_getinfo',
            function ($curl, $option) {
                if ($option === CURLINFO_HEADER_SIZE) {
                    return 69;
                }

                if ($option === CURLINFO_HTTP_CODE) {
                    return 200;
                }
            }
        );

        (new CurlAdapter())->send(new Request('not under test', 'get', [], []));
    }
}
