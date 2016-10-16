<?php

namespace App;

abstract class BaseController
{
    // Variables
        // Cache
    protected $cache_expires_in_day = 86400; // The cache expire in 1 day
    protected $cache_expires_in_hour = 3600; // The cache expire in 1 hour

    // Methods
    public function setResponseCode($code)
    {
        Response::setCode($code);
    }

    public function getResponseCode()
    {
        return Response::getCode();
    }

    public function getInputData()
    {
        return Request::getInputData();
    }

    /**
     * @desc Generate an random key.
     * @param integer $length
     * @return string
     */
    public function generateRandomKey($length = 50)
    {
        return bin2hex(openssl_random_pseudo_bytes($length/2));
    }

    /**
     * @desc Check the if the variables is or contains integer.
     * @param mixed $var
     * @return boolean
     */
    protected function isInteger($var)
    {
        return filter_var($var, FILTER_VALIDATE_INT) !== false;
    }
}
