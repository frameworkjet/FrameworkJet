<?php

/**
 * The purpose of this helper is to provide access to the cache server.
 */

namespace Helpers;

use App\Config;
use Helpers\Log;

class Cache
{
    private static $instance = null;
    private static $prefix = '';

    private function __construct() {}
    private function __clone() {}

    /**
     * @desc Get instance for Memcached.
     * @return null|object
     */
    private static function getInstance()
    {
        if (static::$instance === null) {
            $config  = Config::getByName('Cache');
            self::$prefix = $config['prefix'];

            static::$instance = new \Memcached();
            static::$instance->addServer($config['host'], $config['port']);
            if (static::$instance->getStats() === false) {
                Log::alert('memcache', 'No connection to the server is lost. Host: '.$config['host'].', Post: '.$config['port']);
            }
        }

        return static::$instance;
    }

    /**
     * @desc Set data into the cache.
     * @param string $key
     * @param string $value
     * @param integer $expiration
     */
    public static function set($key, $value, $expiration)
    {
        self::getInstance()->set(self::$prefix.$key, $value, $expiration);
    }

    /**
     * @desc Get data from the cache.
     * @param string $key
     * @return string
     */
    public static function get($key)
    {
        return self::getInstance()->get(self::$prefix.$key);
    }

    /**
     * @desc Return prefix
     * @return string
     */
    public static function getPrefix()
    {
        return self::$prefix;
    }

    /**
     * @desc Delete data from the cache.
     * @param string $key
     */
    public static function delete($key)
    {
        self::getInstance()->delete(self::$prefix.$key);
    }

    /**
     * @desc Delete multiple items from the cache.
     * @param array $keys
     * @param integer $time
     */
    public static function deleteMulti($keys, $time = 0)
    {
        self::getInstance()->deleteMulti($keys, $time);
    }

    /**
     * @desc Change the expiration time of a variables.
     * @param string $key
     * @param integer $expiration
     */
    public static function touch($key, $expiration)
    {
        self::getInstance()->touch(self::$prefix.$key, $expiration);
    }
}
