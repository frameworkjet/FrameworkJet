<?php
/**
 * Manage data required for the work of the framework.
 */

namespace Helpers;

use App\Config;
use App\Response;

class Model
{
    // Variables
    private static $cache_expires_in_month = 2592000; // The cache expire in 1 month

    /**
     * @desc Prepare the data which will be used in some controllers.
     * @param array $data
     * @return array
     */
    public static function prepareData($data = [])
    {
        return array_merge($data, self::getData());
    }

    /**
     * @Desc Returns a bunch of data for some of the controllers.
     * @return array
     */
    public static function getData()
    {
        return [
            'trans' => Config::getByName('Translations/'.Response::getLang()),
            'is_logged' => Session::isLogged()
        ];
    }
}