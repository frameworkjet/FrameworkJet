<?php

namespace App;

class Request
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    private static $inputData = null;
    private static $langsAllowed= [
        'en_UK',
        'es_ES'
    ];



    private function __construct() {}
    private function __clone() {}

    /**
     * @desc Get the clean URI of the request.
     * @return string
     */
    public static function getCleanRequestUrl()
    {
        $rawRequestUrl = $_SERVER['REQUEST_URI'];
        $cleanRequestUrl = strstr($rawRequestUrl, '?', true);

        // If it doesn't content any GET data
        if ($cleanRequestUrl === false) {
            $cleanRequestUrl = $rawRequestUrl;
        }

        // Remote first slash
        return substr($cleanRequestUrl, 1);
    }

    /**
     * @desc Get header with specific key.
     * @param string $key
     * @return string
     */
    public static function getHeader($key)
    {
        return getallheaders()[$key];
    }

    /**
     * @desc Get the HTTP code of the request.
     * @return mixed
     */
    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @desc Get the content type of the request.
     * @return mixed
     */
    public static function getContentType()
    {
        return $_SERVER['CONTENT_TYPE'];
    }

    /**
     * @desc Get the IP address of the requester.
     * @return mixed
     */
    public static function getRemoteAddress()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @desc Get the lang
     * @return mixed
     */
    public static function getLang()
    {
        return isset($_GET['lang']) && in_array($_GET['lang'], self::$langsAllowed) ? $_GET['lang'] : (is_null($_SESSION['lang']) ? App::config('DEFAULT_LANGUAGE') : $_SESSION['lang']);
    }

    /**
     * @desc Get the input data if there is any.
     * @return array|null
     */
    public static function getInputData()
    {
        if (self::$inputData === null) {
            $inputData = [];
            $rawInput = file_get_contents('php://input');

            if (!empty($rawInput)) {
                mb_parse_str($rawInput, $inputData);
            }

            self::$inputData = $inputData;
        }

        return self::$inputData;
    }
}
