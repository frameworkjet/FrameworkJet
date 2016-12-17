<?php

/**
 * The purpose of this helper is to keep the user session.
 */

namespace Helpers;

class Session
{
    private static $expires_in = 3153600000; // 100 years



    private function __construct() {}
    private function __clone() {}

    /**
     * @desc Check if the user is logged or no
     * @return bool
     */
    public static function isLogged()
    {
        if (isset($_SESSION['user_access_data']['access_token'])) {
            if (!isset($_COOKIE['is_logged']) || !isset($_COOKIE['access_code'])) {
                self::setByArray($_SESSION['user_access_data']);
            }

            return true;
        } elseif (isset($_COOKIE['access_code']) && ($user_access_data = Cache::get('user_access_data_'.$_COOKIE['access_code'])) !== false) {
            self::setByArray($user_access_data);

            return true;
        }

        self::delete();

        return false;
    }

    /**
     * @desc Get access token.
     * @return bool|string
     */
    public static function getAccessToken()
    {
        if (self::isLogged() === false) {
            self::delete();
        }

        return $_SESSION['user_access_data']['access_token'];
    }

    /**
     * @desc Get token type
     * @return string
     */
    public static function getTokenType()
    {
        return $_SESSION['user_access_data']['token_type'];
    }

    /**
     * @desc Get refresh token
     * @return string
     */
    public static function getRefreshToken()
    {
        return $_SESSION['user_access_data']['refresh_token'];
    }

    /**
     * @desc Get the date of expiration
     * @return string
     */
    public static function getExpiresOn()
    {
        return $_SESSION['user_access_data']['expires_on'];
    }

    /**
     * @desc Get the user id
     * @return integer
     */
    public static function getUserId()
    {
        return $_SESSION['user_access_data']['user_id'];
    }

    /**
     * @desc Returns the header used to authenticate the user
     * @return string
     */
    public static function getHeader()
    {
        return 'Authorization: '.Session::getTokenType().' '.Session::getAccessToken();
    }

    /**
     * @desc Set access token.
     * @param string $access_token
     * @param string $token_type
     * @param string $refresh_token
     * @param string $expires_on
     * @param integer $user_id
     * @param bool|string  $access_code
     * @param bool|integer $expires_on_unix
     */
    public static function set($access_token, $token_type, $refresh_token, $expires_on, $user_id = false, $access_code = false, $expires_on_unix = false)
    {
        // Generate variables
        $access_code = $access_code ? $access_code : self::generateRandomKey();
        $expires_on_unix = $expires_on_unix ? $expires_on_unix : strtotime($expires_on);

        // Set cookies
        setcookie('is_logged', 1, $expires_on_unix, '/');
        setcookie('access_code', $access_code, $expires_on_unix, '/');

        // Set all data to a SESSION variable
        $_SESSION['user_access_data'] = [
            'access_code' => $access_code,
            'access_token' => $access_token,
            'token_type' => ucfirst($token_type),
            'refresh_token' => $refresh_token,
            'expires_on' => $expires_on,
            'expires_on_unix' => $expires_on_unix,
            'user_id' => $user_id
        ];

        // Save in the cache
        $expiration = $expires_on_unix - time();
        $expiration = $expiration > 2592000 ? 2592000 : $expiration; // 2592000 seconds = 30 days
        Cache::set('user_access_data_'.$access_code, $_SESSION['user_access_data'], $expiration);
    }

    /**
     * @desc Delete the access token.
     */
    public static function delete()
    {
        $_SESSION['user_access_data'] = false;
        Cache::delete('user_access_data_'.$_COOKIE['access_code']);

        setcookie('is_logged', 0, time() + self::$expires_in, '/');
        setcookie('access_code', '', time() - 3600, '/');
    }

    /**
     * @desc Set the user session by array
     * @param array $user_access_data
     */
    private static function setByArray($user_access_data)
    {
        self::set(
            $user_access_data['access_token'],
            $user_access_data['token_type'],
            $user_access_data['refresh_token'],
            $user_access_data['expires_on'],
            $user_access_data['user_id'],
            $user_access_data['access_code'],
            $user_access_data['expires_on_unix']
        );
    }

    /**
     * @desc Generate an random key.
     * @param integer $length
     * @return string
     */
    private function generateRandomKey($length = 50)
    {
        return bin2hex(openssl_random_pseudo_bytes($length/2));
    }
}