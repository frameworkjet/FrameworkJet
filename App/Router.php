<?php

namespace App;

class Router
{
    // First bit is for POST.
    // Second bit is for GET.
    // Third bit is for PUT.
    // Forth bit is for DELETE

    const POST                = 1;
    const GET                 = 2;
    const POST_GET            = 3;
    const PUT                 = 4;
    const POST_PUT            = 5;
    const GET_PUT             = 6;
    const POST_GET_PUT        = 7;
    const DELETE              = 8;
    const POST_DELETE         = 9;
    const GET_DELETE          = 10;
    const POST_GET_DELETE     = 11;
    const PUT_DELETE          = 12;
    const POST_PUT_DELETE     = 13;
    const GET_PUT_DELETE      = 14;
    const POST_GET_PUT_DELETE = 15;
    const ALL = self::POST_GET_PUT_DELETE;

    private static $matchMethodToIndex = [
        'POST' => self::POST,
        'GET' => self::GET,
        'PUT' => self::PUT,
        'DELETE' => self::DELETE
    ];

    private static $matchedRouteParameters = [];



    private function __construct() {}
    private function __clone() {}

    /**
     * @desc Get the router configurations.
     * @return array
     */
    private static function getConfig()
    {
        return Config::getByName('Router');
    }

    /**
     * @desc Get the routers.
     * @return mixed
     */
    private static function getRoutes()
    {
        return self::getConfig()['routes'];
    }

    /**
     * Get the patterns.
     * @return mixed
     */
    private static function getGlobalPattern()
    {
        return self::getConfig()['globalPattern'];
    }

    /**
     * @param string $requestMethod
     * @param string $requestResourceUrl
     * @return array of matched resource controller and action, if not matched return null
     */
    public static function getMatchedRouterResource($requestMethod, $requestResourceUrl)
    {
        foreach (self::getRoutes() as $routePattern => $route) {
            if (self::isMatchRequestType($requestMethod, $route[0])) {
                $localUrlPattern = isset($route[2]) ? $route[2] : [];

                if (self::isMatchResourceUrl($requestResourceUrl, $routePattern, $localUrlPattern)){
                    // Route matched, stop checking other router.
                    return self::parseResourceName($route[1]);
                }
            }
        }

        return null;
    }

    /**
     * @desc Parse the resource name.
     * @param string $resourceName
     * @return array
     */
    private static function parseResourceName($resourceName)
    {
        $strPosName = strpos($resourceName, '\\');

        return [substr($resourceName, 0, $strPosName), substr($resourceName, ++$strPosName)];
    }

    /**
     * @desc Return matched route parameters.
     * @return mixed
     */
    public static function getMatchedRouteParameters()
    {
        return self::$matchedRouteParameters;
    }

    /**
     * @desc Store the matched parameters in a variable.
     * @param $matchedRouteParameters
     */
    private static function setMatchedRouteParameters($matchedRouteParameters)
    {
        self::$matchedRouteParameters = $matchedRouteParameters;
    }

    /**
     * @desc The HTTP code of the request, does it match with the HTTP code from the router's rule.
     * @param $requestMethod
     * @param $allowedRequestMethod
     * @return bool
     */
    private static function isMatchRequestType($requestMethod, $allowedRequestMethod)
    {
        $requestMethodBitwiseValue = self::$matchMethodToIndex[$requestMethod];

        return (($requestMethodBitwiseValue & $allowedRequestMethod) === $requestMethodBitwiseValue);
    }

    /**
     * @desc Check if the URI matches any of the global rules.
     * @param $requestResourceUrl
     * @param $routeResourceUrl
     * @param $localRoutePattern
     * @return bool
     */
    private static function isMatchResourceUrl($requestResourceUrl, $routeResourceUrl, $localRoutePattern)
    {
        // Merge local and global pattern, local must overview global
        $routePatterns = $localRoutePattern + self::getGlobalPattern();

        // Applying patterns to router resource URL
        $rawRouteResourceUrl = explode('/', $routeResourceUrl);
        $routeResourceUrl = strtr($routeResourceUrl, $routePatterns);
        $matchedRouteParameters = [];

        $isMatched = (bool) preg_match('#^'.$routeResourceUrl.'$#', $requestResourceUrl, $matchedRouteParameters);
        if ($isMatched) {
            $requestResourceUrl = explode('/', $requestResourceUrl);
            $params = [];
            $mapping = false;

            foreach ($requestResourceUrl as $key => $value) {
                if (!isset($rawRouteResourceUrl[$key]) || isset($rawRouteResourceUrl[$key]) && $value != $rawRouteResourceUrl[$key]) {
                    if (!isset($rawRouteResourceUrl[$key])) {
                        if (!$mapping) {
                            $mapping = key($params);
                        }

                        $params[$mapping] .= '/'.$value;
                    } else {
                        $params[substr($rawRouteResourceUrl[$key], 1)] = $value;
                    }
                }
            }

            self::setMatchedRouteParameters($params);
        }

        return $isMatched;
    }
}