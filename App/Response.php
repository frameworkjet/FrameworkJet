<?php

namespace App;

class Response
{
    private static $code = 200;
    private static $headers = [];
    private static $format;
    private static $template = '';
    private static $params = [];
    private static $body = '';



    private function __construct() {}
    private function __clone() {}

    /**
     * @desc Set the HTTP Code.
     * @param integer $code
     */
    public static function setCode($code)
    {
        self::$code = $code;
    }

    /**
     * @desc Get the HTTP Code.
     * @return integer
     */
    public static function getCode()
    {
        return self::$code;
    }

    /**
     * @desc Set the Template.
     * @param string $template
     */
    public static function setTemplate($template)
    {
        self::$template = $template;
    }

    /**
     * @desc Get the Template.
     * @return string
     */
    public static function getTemplate()
    {
        return self::$template;
    }

    /**
     * @desc Set the parameters.
     * @param array $params
     */
    public static function setParams($params)
    {
        self::$params = $params;
    }

    /**
     * @desc Get the parameters.
     * @return array
     */
    public static function getParams()
    {
        return self::$params;
    }

    /**
     * @desc Set the format of the response
     * @param boolean|string $format
     */
    public static function setFormat($format = false)
    {
        if ($format) {
            self::$format = $format;
        } else if (!self::$format) {
            self::$format = App::config('DEFAULT_RESPONSE_FORMAT');
        }
    }

    /**
     * @desc Get the format of the response.
     * @return integer
     */
    public static function getFormat()
    {
        return self::$format;
    }

    /**
     * @desc Set the lang
     * @param string $lang
     */
    public static function setLang($lang)
    {
        $_SESSION['lang'] = $lang;
    }

    /**
     * @desc Get the lang
     * @return mixed
     */
    public static function getLang()
    {
        return $_SESSION['lang'];
    }

    /**
     * @desc Set the body of the response.
     * @param mixed $body
     */
    public static function setBody($body)
    {
        self::$body = $body;
    }

    /**
     * @desc Render the response of the App framework.
     */
    public static function render()
    {
        self::setFormat(); // Set the default format if we haven't passed it
        self::sendHeaders();
        self::sendBody();
    }

    /**
     * @desc Set the header of the response.
     */
    private static function sendHeaders()
    {
        http_response_code(self::getCode());
        if (self::getFormat() == 'html') {
            header('Content-type: text/html');
        }
    }

    /**
     * @desc Send the body of the response.
     * @return mixed
     */
    private static function sendBody()
    {
        if (self::getCode() != '200') {
            self::setTemplate('error'.self::getCode().App::twigFileExt);
        }

        // Wrap the response in a template dedicated for errors
        $loader = new \Twig_Loader_Filesystem(App::getRootDir().'Templates/');
        $twig = new \Twig_Environment($loader, array('cache' => App::getRootDir() . '/cache',));
        self::setBody($twig->render(
            self::getTemplate(),
            array_merge(self::getParams(), ['trans' => Config::getByName('Translations/'.self::getLang()), 'lang' => self::getLang()])
        ));

        echo self::$body;
    }
}
