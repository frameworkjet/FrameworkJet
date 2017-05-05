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
    private static $cookie_expire = 3153600000; // 100 years



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
     * @desc Check if the language is set
     * @return bool
     */
    public static function isLangSet()
    {
        return  (isset($_SESSION['lang']) && isset($_SESSION['lang_code']));
    }

    /**
     * @desc Set the lang
     * @param string $lang
     */
    public static function setLang($lang)
    {
        // Set language
        $_SESSION['lang'] = $lang;
        setcookie('lang', $lang, time() + self::$cookie_expire, '/', Config::getByName('App')['DEFAULT_DOMAIN']);

        // Set language code
        $lang_code = App::config('ALLOWED_LANGUAGES_CODES')[$lang];
        $_SESSION['lang_code'] = $lang_code;
        setcookie('lang_code', $lang_code, time() + self::$cookie_expire, '/', Config::getByName('App')['DEFAULT_DOMAIN']);
    }

    /**
     * @desc Get the lang
     * @return mixed
     */
    public static function getLang()
    {
        return $_SESSION['lang'] ?? false;
    }

    /**
     * @desc Get the lang code
     * @return mixed
     */
    public static function getLangCode()
    {
        return $_SESSION['lang_code'] ?? false;
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
     * @desc Check if the response format is JSON.
     * @return bool if it is JSON
     */
    public static function isFormatJson()
    {
        return self::getFormat() == 'json';
    }

    /**
     * @desc Check if the response format is CSV.
     * @return bool if it is CSV
     */
    public static function isFormatCsv()
    {
        return self::getFormat() == 'csv';
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
        } else if (self::getFormat() == 'json') {
            header('Content-type: application/'.self::getFormat());
        } else if (self::isFormatCsv()){
            header("Content-type: text/csv");
            header("Content-Disposition: attachment; filename=export.csv");
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Pragma: no-cache");
            header("Expires: 0");
        }
    }

    /**
     * @desc Send the body of the response.
     * @return mixed
     */
    private static function sendBody()
    {
        // First check if we have a non-standard request
        if (self::isFormatJson()) {
            echo json_encode(self::$params);
        } else if (self::isFormatCsv()) {
            $output = fopen("php://output", "w");
            foreach (self::$params as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
        } else {
            if (self::getCode() != '200') {
                self::setTemplate('error'.self::getCode().App::twigFileExt);
            }

            // Wrap the response in a template dedicated for errors
            $debug = Config::getByName('App')['DEBUG'];
            $loader = new \Twig_Loader_Filesystem(App::getRootDir().'Templates/');
            $twig = new \Twig_Environment($loader, array('debug' => $debug, 'cache' => App::getRootDir() . '/cache',));
            if ($debug) {
                $twig->addExtension(new \Twig_Extension_Debug());
            }
            self::setBody($twig->render(
                self::getTemplate(),
                array_merge(self::getParams(), ['lang' => self::getLang(), 'lang_code' => self::getLangCode()])
            ));

            echo self::$body;
        }
    }
}