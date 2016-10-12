<?php

/**
 * FrameworkJet - A PHP Framework, based on the core of APIJet
 *
 * @author Pavel Tashev, Gyuner Zeki
 * @desc The current package is based on APIJet framework (URL: https://github.com/APIJet/APIJet) written by
 * Gyuner Zeki. The initial version of the APIJet framework has been modified in order to fit to the needs
 * of the current framework (FrameworkJet).
 * @web https://github.com/pavel-tashev/FrameworkJet
 * @copyright 2016 Pavel Tashev
 * @package FrameworkJet
 * @version 1.0.0
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NON-INFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace App;

/**
 * App
 * @package  FrameworkJet
 * @author   Pavel Tashev, Gyuner Zeki
 * @since    1.0.0
 *
 */
class App
{
    /**
     * Definition of few constants.
     *
     * @const string
     */
    const fileExt = '.php';
    const twigFileExt = '.html.twig';

    /**
     * Contains the root directory.
     *
     * @var null
     */
    private static $rootDir = null;

    /**
     * Configuration of the App.
     *
     * @var null
     */
    private static $appConfig = null;

    /**
     * Configuration constants.
     */
    const DEFAULT_RESPONSE_FORMAT = 'html';
    const DEFAULT_TIMEZONE = 'UTC';

    /********************************************************************************
     * Basic methods
     *******************************************************************************/
    /**
     * @desc Constructor.
     */
    private function __construct() {}



    /********************************************************************************
     * PSR-0 Autoloader
     *******************************************************************************/

    /**
     * @desc Register App's PSR-0 autoloader and also load the composer autoloader.
     */
    public static function registerAutoload()
    {
        require realpath(dirname(__FILE__) . '/../').'/vendor/autoload.php';
        spl_autoload_register(__NAMESPACE__ . '\\App::autoload');
    }

    /**
     * @desc Autoload all PHP files located in the api directory.
     * @param string $className
     */
    public static function autoload($className)
    {
        // Get the file name
        $className = ltrim($className, '\\');
        $fileName  = '';

        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . self::fileExt;

        // Require/Load the file if it exists
        $filePath = self::getRootDir() . '' . $fileName;
        if (file_exists($filePath)) {
            require $filePath;
        }
    }



    /********************************************************************************
     * Helper Methods
     *******************************************************************************/

    /**
     * @desc Get the absolute path to the application's root directory.
     * @return string
     */
    public static function getRootDir()
    {
        if (self::$rootDir === null) {
            self::$rootDir = realpath(dirname(__FILE__) . '/../') . DIRECTORY_SEPARATOR;
        }

        return self::$rootDir;
    }



    /********************************************************************************
     * Instantiation and Configuration
     *******************************************************************************/

    /**
     * @desc Get the configuration of the App.
     * @param string $propertyName
     * @return mixed
     */
    public static function config($propertyName)
    {
        if (self::$appConfig === null) {
            self::$appConfig = Config::getByName('App');
        }

        return isset(self::$appConfig[$propertyName]) ? self::$appConfig[$propertyName] : App::$propertyName;
    }



    /********************************************************************************
     * Runner
     *******************************************************************************/

    /**
     * @desc Check if the request matches any of the router rules and if so execute the corresponding controller's method.
     */
    public static function run()
    {
        // Check if the request will match any of the routers rules.
        if (!in_array(Request::getMethod(), Request::getHttpRequestsAllowed())) {
            Response::setCode(404);
            return;
        }

        // Check if the request will match any of the routers rules.
        $matchedResource = Router::getMatchedRouterResource(Request::getMethod(), Request::getCleanRequestUrl());
        if ($matchedResource === null) {
            Response::setCode(404);
            return;
        }

        // Default configurations
        date_default_timezone_set(self::config('DEFAULT_TIMEZONE'));
        if (Response::getLang() != Request::getLang()) {
            Response::setLang(Request::getLang());
        }

        try {
            // Check if template file exists
            //if (!file_exists(self::getRootDir().'Templates/'.$matchedResource[0].'/'.$matchedResource[0].$matchedResource[1].self::twigFileExt))  {
            //    $response = false;
            //} else {
                // Call the required controller's method which corresponds to the matched router.
                Response::setTemplate($matchedResource[0].'/'.$matchedResource[0].$matchedResource[1].self::twigFileExt);
                $response = self::executeResourceAction($matchedResource[0], $matchedResource[1], Router::getMatchedRouteParameters());
            //}

            // Set the response.
            if ($response === false) {
                Response::setCode(404);
            } else {
                Response::setParams($response);
            }
        } catch (CustomException $e) {
            Response::setCode($e->getHttpCode());
            Response::setParams(['error' => $e->getErrorBody()]);
        } catch (\Exception $e) {
            Response::setCode(404);
        }
    }

    /**
     * @desc Execute the controller's method corresponding to the request.
     * @param string $controller
     * @param string $action
     * @param string $parameters
     * @return response of executed action or false in case it doesn't exist
     */
    private static function executeResourceAction($controller, $action, $parameters)
    {
        // Prepare a few parameters
        $controller = ucfirst($controller);
        $action = strtolower(Request::getMethod()).$action;

        // Check if controller file exist
        if (!file_exists(self::getRootDir().'Controllers/'.$controller.'Controller'.self::fileExt))  {
            return false;
        }
        require self::getRootDir().'Controllers/'.$controller.'Controller'.self::fileExt;

        $controller = 'Controller\\'.$controller;

        // Check if class exist
        if (!class_exists($controller)) {
            return false;
        }

        $controllerInstance = new $controller();

        // Check if action exist
        if (!method_exists($controllerInstance, $action)) {
            return false;
        }

        // Check if it's callable
        if (!is_callable([$controllerInstance, $action])) {
            return false;
        }

        return call_user_func_array(array($controllerInstance, $action), $parameters);
    }
}
