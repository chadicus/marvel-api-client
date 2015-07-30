<?php
namespace Chadicus\Marvel\Api;

/**
 * Unit tests for \Chadicus\Marvel\Api\CurlAdapter class.
 *
 * @coversDefaultClass \Chadicus\Marvel\Api\CurlAdapter
 * @covers ::<private>
 */
final class CurlAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tear down each test.
     *
     * @return void
     */
    public function tearDown()
    {
        GlobalFunctions::reset();
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
        GlobalFunctions::$curlInit = function () {
            return true;
        };

        GlobalFunctions::$curlSetoptArray = function ($curl, array $options) {
            return true;
        };

        GlobalFunctions::$curlExec = function ($curl) {
            return "HTTP/1.1 200 OK\r\nContent-Length: 13\r\nContent-Type: application/json\r\n\n{\"foo\":\"bar\"}";
        };

        GlobalFunctions::$curlError = function ($curl) {
            return '';
        };

        GlobalFunctions::$curlGetinfo = function ($curl, $option) {
            if ($option === CURLINFO_HEADER_SIZE) {
                return 69;
            }

            if ($option === CURLINFO_HTTP_CODE) {
                return 200;
            }
        };

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
        GlobalFunctions::$curlInit = function () {
            return true;
        };

        $actualHeaders = [];

        GlobalFunctions::$curlSetoptArray = function ($curl, array $options) use (&$actualHeaders) {
            $actualHeaders = $options[CURLOPT_HTTPHEADER];
            return true;
        };

        GlobalFunctions::$curlExec = function ($curl) {
            return "HTTP/1.1 200 OK\r\nContent-Length: 4\r\nContent-Type: application/json\r\n\n[]";
        };

        GlobalFunctions::$curlError = function ($curl) {
            return '';
        };

        GlobalFunctions::$curlGetinfo = function ($curl, $option) {
            if ($option === CURLINFO_HEADER_SIZE) {
                return 69;
            }

            if ($option === CURLINFO_HTTP_CODE) {
                return 200;
            }
        };

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
        GlobalFunctions::$curlInit = function () {
            return false;
        };

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
        GlobalFunctions::$curlSetoptArray = function () {
            return false;
        };

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
        GlobalFunctions::$curlExec = function () {
            return false;
        };

        GlobalFunctions::$curlError = function () {
            return 'the error';
        };

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
        GlobalFunctions::$curlInit = function () {
            return true;
        };

        GlobalFunctions::$curlSetoptArray = function ($curl, array $options) {
            return true;
        };

        GlobalFunctions::$curlExec = function ($curl) {
            return "HTTP/1.1 200 OK\r\nContent-Length: 2\r\nContent-Type: application/json\r\n\n[]";
        };

        GlobalFunctions::$curlError = function ($curl) {
            return '';
        };

        GlobalFunctions::$curlGetinfo = function ($curl, $option) {
            if ($option === CURLINFO_HEADER_SIZE) {
                return false;
            }
        };

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
        GlobalFunctions::$curlInit = function () {
            return true;
        };

        GlobalFunctions::$curlSetoptArray = function ($curl, array $options) {
            return true;
        };

        GlobalFunctions::$curlExec = function ($curl) {
            return "HTTP/1.1 200 OK\r\nContent-Length: 4\r\nContent-Type: application/json\r\n\n[]";
        };

        GlobalFunctions::$curlError = function ($curl) {
            return '';
        };

        GlobalFunctions::$curlGetinfo = function ($curl, $option) {
            if ($option === CURLINFO_HEADER_SIZE) {
                return 69;
            }

            if ($option === CURLINFO_HTTP_CODE) {
                return false;
            }
        };

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
        GlobalFunctions::$curlInit = function () {
            return true;
        };

        GlobalFunctions::$curlSetoptArray = function ($curl, array $options) {
            return true;
        };

        GlobalFunctions::$curlExec = function ($curl) {
            // contains syntax error
            return "HTTP/1.1 200 OK\r\nContent-Length: 4\r\nContent-Type: application/json\r\n\n{xx}}";
        };

        GlobalFunctions::$curlError = function ($curl) {
            return '';
        };

        GlobalFunctions::$curlGetinfo = function ($curl, $option) {
            if ($option === CURLINFO_HEADER_SIZE) {
                return 69;
            }

            if ($option === CURLINFO_HTTP_CODE) {
                return 200;
            }
        };

        (new CurlAdapter())->send(new Request('not under test', 'get', [], []));
    }
}
