<?php
/**
 * Creates cURL requests
 */

namespace Helpers;

class Curl
{
    /**
     * @desc Executes DELETE|GET|POST|PUT request.
     * @param string $method Http method
     * @param string $request_url URL
     * @param array $params
     * @param array $header
     * @param string|bool $format
     * @param bool $with_file
     * @return array
     */
    public static function request($method, $request_url, $params = [], $header = [], $format = false, $with_file = false)
    {
        // Set header according to the format
        if (!$with_file) {
            switch ($format) {
                case 'json':
                    $header[] = 'Content-Type: application/json';
                    $params = ($method != 'GET') ? json_encode($params) : http_build_query($params);
                    break;
                default:
                    $params = http_build_query($params);
                    break;
            }
        }

        // Initialize
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        // Prepare the query
        if ($method == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        } else if ($method == 'GET') {
            $request_url .= $params ? '?'.$params : '';
        } else if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        } else if ($method == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        // Send request and get data
        $response = curl_exec($ch);
        $response_info = curl_getinfo($ch);

        // Close Curl
        curl_close($ch);

        // Prepare the response
        $body = substr($response, $response_info['header_size']);
        if ($format == 'json') {
            $body = json_decode($body, true);
        }

        // Return data
        return ['http_code' => $response_info['http_code'], 'body' => $body];
    }
}