<?php
namespace Chadicus\Marvel\Api;

/**
 * This class if for overriding global functions for use within unit tests.
 */
final class GlobalFunctions
{
    /**
     * The custom value to return when calling \time().
     *
     * @var callable
     */
    public static $time = null;

    /**
     * The custom curl_init implementation to call during these tests.
     *
     * @var Closure
     */
    public static $curlInit = null;

    /**
     * The custom curl_setopt_array implementation to call during these tests.
     *
     * @var Closure
     */
    public static $curlSetoptArray = null;

    /**
     * The custom curl_exec implementation to call during these tests.
     *
     * @var Closure
     */
    public static $curlExec = null;

    /**
     * The custom curl_error implementation to call during these tests.
     *
     * @var Closure
     */
    public static $curlError = null;

    /**
     * The custom curl_getinfo implementation to call during these tests.
     *
     * @var Closure
     */
    public static $curlGetinfo = null;

    /**
     * The custom json_last_error implementation to call during these tests.
     *
     * @var Closure
     */
    public static $jsonLastError = null;

    /**
     * The custom extension_loaded implementation to call during these tests.
     *
     * @var Closure
     */
    public static $extensionLoaded = null;

    /**
     * Sets all custom function properties to null.
     *
     * @return void
     */
    public static function reset()
    {
        self::$time = null;
        self::$curlInit = null;
        self::$curlSetoptArray = null;
        self::$curlExec = null;
        self::$curlError = null;
        self::$curlGetinfo = null;
        self::$jsonLastError = null;
        self::$extensionLoaded = null;
    }
}

/**
 * Custom implementation of \time() to make testing more simple.
 *
 * @return 1
 */
function time()
{
    if (GlobalFunctions::$time !== null) {
        return call_user_func(GlobalFunctions::$time);
    }

    return \time();
}

/**
 * Custom override of \curl_init().
 *
 * @return mixed
 */
function curl_init()
{
    if (GlobalFunctions::$curlInit !== null) {
        return call_user_func(GlobalFunctions::$curlInit);
    }

    return \curl_init();
}

/**
 * Custom override of \curl_setopt_array().
 *
 * @param resource $curl    A cURL handle returned by curl_init().
 * @param array    $options An array specifying which options to set and their values.
 *
 * @return boolean
 */
function curl_setopt_array($curl, array $options)
{
    if (GlobalFunctions::$curlSetoptArray !== null) {
        return call_user_func_array(GlobalFunctions::$curlSetoptArray, [$curl, $options]);
    }

    return \curl_setopt_array($curl, $options);
}

/**
 * Custom override of \curl_exec().
 *
 * @param resource $curl A cURL handle returned by curl_init().
 *
 * @return string
 */
function curl_exec($curl)
{
    if (GlobalFunctions::$curlExec !== null) {
        return call_user_func(GlobalFunctions::$curlExec, [$curl]);
    }

    return \curl_exec($curl);
}

/**
 * Custom override of \curl_error().
 *
 * @param resource $curl A cURL handle returned by curl_init().
 *
 * @return string
 */
function curl_error($curl)
{
    if (GlobalFunctions::$curlError !== null) {
        return call_user_func(GlobalFunctions::$curlError, [$curl]);
    }

    return \curl_error($curl);
}

/**
 * Custom override of \curl_getinfo().
 *
 * @param resource $curl   A cURL handle returned by curl_init().
 * @param integer  $option The option to return.
 *
 * @return string
 */
function curl_getinfo($curl, $option = 0)
{
    if (GlobalFunctions::$curlGetinfo !== null) {
        return call_user_func_array(GlobalFunctions::$curlGetinfo, [$curl, $option]);
    }

    return \curl_getinfo($curl, $option);
}

/**
 * Custom override of \extension_loaded().
 *
 * @param string $name The extension name.
 *
 * @return boolean
 */
function extension_loaded($name)
{
    if (GlobalFunctions::$extensionLoaded !== null) {
        return call_user_func(GlobalFunctions::$extensionLoaded, [$name]);
    }

    return \extension_loaded($name);
}
