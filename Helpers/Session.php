<?php

/**
 * The purpose of this helper is to keep the user session.
 */

namespace Helpers;

class Session
{
    private static $cookie_expire = 31536000000; // 1000 years



    private function __construct() {}
    private function __clone() {}

    /**
     * @desc Get access token.
     * @return bool|string
     */
    public static function getAccessToken()
    {
        if (!isset($_COOKIE['is_logged'])) {
            setcookie('is_logged', false, self::$cookie_expire);
        }

        if (!isset($_SESSION['access_token'])) {
            $_SESSION['access_token'] = $_SESSION['token_type'] = $_SESSION['refresh_token'] = $_SESSION['expires_on'] = false;
        }

        return $_SESSION['access_token'];
    }

    /**
     * @desc Get token type
     * @return string
     */
    public static function getTokenType()
    {
        return $_SESSION['token_type'];
    }

    /**
     * @desc Get refresh token
     * @return string
     */
    public static function getRefreshToken()
    {
        return $_SESSION['refresh_token'];
    }

    /**
     * @desc Get the date of expiration
     * @return string
     */
    public static function getExpiresOn()
    {
        return $_SESSION['expires_on'];
    }

    /**
     * @desc Set access token.
     * @param string $access_token
     * @param string $token_type
     * @param string $refresh_token
     * @param string $expires_on
     */
    public static function set($access_token, $token_type, $refresh_token, $expires_on)
    {
        $_SESSION['access_token'] = $access_token;
        $_SESSION['token_type'] = ucfirst($token_type);
        $_SESSION['refresh_token'] = $refresh_token;
        $_SESSION['expires_on'] = $expires_on;

        setcookie('is_logged', true, self::$cookie_expire);
    }

    /**
     * @desc Delete the access token.
     */
    public static function delete()
    {
        $_SESSION['access_token'] = $_SESSION['token_type'] = $_SESSION['refresh_token'] = $_SESSION['expires_on'] = false;
        setcookie('is_logged', false, self::$cookie_expire);
    }
}