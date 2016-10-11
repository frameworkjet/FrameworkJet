<?php

/**
 * Curl requests to the API.
 */

namespace Helpers;

use App\Config;

class Api
{
    /**
     * Url.
     * @var string
     */
    private static $request_url = null;

     /**
     * Response format.
     * @var string
     */
    private static $format = 'json';

    /**
     * HTTP code of the response.
     * @var integer
     */
    private static $http_code;

    /**
     * Header of the response.
     * @var mixed
     */
    private static $header;

    private function __construct() {}
    private function __clone() {}

    /**
     * @desc Configure the request.
     */
    private static function config()
    {
        if (static::$request_url === null) {
            $config = Config::getByName('Services')['api'];

            self::$request_url = $config['url'];
            self::$format = $config['format'];
        }
    }

    /**
     * @desc Executes DELETE|GET|POST|PUT request.
     * @param $method Http method
     * @param $request_url URL
     * @param $params Input parameters
     * @return mixed
     */
    private static function request($method, $request_url, $params)
    {
        // Configuration
        self::config();
        $request_url = self::$request_url.$request_url;

        // Initialize
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        // Prepare the query
        if ($method == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } else if ($method == 'GET') {
            $request_url .= '&' . http_build_query($params);
        } else if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } else if ($method == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/'.self::$format));
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Get the Response and info for the header and the http code
        $response = curl_exec($ch);
        $response_info = curl_getinfo($ch);

        // Close Curl
        curl_close($ch);

        // Get the HTTP code and separate the response header from the response body
        $response_info['http_code']; //self::$http_code
        trim(substr($response, 0, $response_info['header_size'])); //self::$header
        $response_body = substr($response, $response_info['header_size']);

        return $response_body;
    }
}