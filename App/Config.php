<?php

namespace App;

class Config
{
    private static $configStore = [];
    private static $baseConfigDir = null;

    private function __construct() {}
    private function __clone() {}

    /**
     * @desc If config with corresponding file doesn't exist, it will return an empty array.
     * @param sting $name
     * @return array
     */
    public static function getByName($name)
    {
        if (!isset(self::$configStore[$name])) {
            $configFile = @include self::getBaseConfigDir().$name.App::fileExt;
            self::$configStore[$name] = $configFile === false ? [] : $configFile;
        }

        return self::$configStore[$name];
    }

    /**
     * @desc Return the base directory with the configuration files.
     * @return null|string
     */
    private static function getBaseConfigDir()
    {
        if (self::$baseConfigDir === null) {
            self::$baseConfigDir = App::getRootDir().'Config'.DIRECTORY_SEPARATOR;
        }

        return self::$baseConfigDir;
    }
}
