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
}
