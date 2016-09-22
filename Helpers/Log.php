<?php
/**
 * Error logger.
 */

namespace Helpers;

use App\App;
use App\Config;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{
    private static $logs_dir_path = '';

    private static function getLogsDir()
    {
        return self::$logs_dir_path == '' ? self::$logs_dir_path = App::getRootDir().Config::getByName('App')['DEFAULT_LOGS_DIR'] : self::$logs_dir_path;
    }

    public static function emergency($channel_name, $message)
    {
        $log = new Logger($channel_name);
        $log->pushHandler(new StreamHandler(self::getLogsDir().$channel_name.'-error.log.'.date('Y\-m\-d'), Logger::EMERGENCY));
        $log->emergency($message);
    }

    public static function alert($channel_name, $message)
    {
        $log = new Logger($channel_name);
        $log->pushHandler(new StreamHandler(self::getLogsDir().$channel_name.'-error.log.'.date('Y\-m\-d'), Logger::ALERT));
        $log->alert($message);
    }

    public static function critical($channel_name, $message)
    {
        $log = new Logger($channel_name);
        $log->pushHandler(new StreamHandler(self::getLogsDir().$channel_name.'-error.log.'.date('Y\-m\-d'), Logger::ALERT));
        $log->alert($message);
    }

    public static function error($channel_name, $message)
    {
        $log = new Logger($channel_name);
        $log->pushHandler(new StreamHandler(self::getLogsDir().$channel_name.'-error.log.'.date('Y\-m\-d'), Logger::ALERT));
        $log->alert($message);
    }

    public static function warning($channel_name, $message)
    {
        $log = new Logger($channel_name);
        $log->pushHandler(new StreamHandler(self::getLogsDir().$channel_name.'-error.log.'.date('Y\-m\-d'), Logger::ALERT));
        $log->alert($message);
    }

    public static function notice($channel_name, $message)
    {
        $log = new Logger($channel_name);
        $log->pushHandler(new StreamHandler(self::getLogsDir().$channel_name.'-error.log.'.date('Y\-m\-d'), Logger::ALERT));
        $log->alert($message);
    }

    public static function info($channel_name, $message)
    {
        $log = new Logger($channel_name);
        $log->pushHandler(new StreamHandler(self::getLogsDir().$channel_name.'-error.log.'.date('Y\-m\-d'), Logger::ALERT));
        $log->alert($message);
    }

    public static function debug($channel_name, $message)
    {
        $log = new Logger($channel_name);
        $log->pushHandler(new StreamHandler(self::getLogsDir().$channel_name.'-error.log.'.date('Y\-m\-d'), Logger::ALERT));
        $log->alert($message);
    }
}
