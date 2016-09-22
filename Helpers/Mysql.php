<?php

/**
 * The purpose of this helper is to provide access to the MySQL database.
 */

namespace Helpers;

use App\Config;
use Helpers\Log;
use \PDO;

class Mysql
{
    private static $instance = null;
    private static $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    ];

    private function __construct() {}
    private function __clone() {}

    /**
     * @desc Connect to the database.
     * @return null|PDO
     */
    private static function getInstance()
    {
        if (self::$instance === null) {
            $config = Config::getByName('SqlDatabase');

            self::$instance = new \PDO('mysql:host='.$config['host'].';dbname='.$config['database'],
                $config['username'],
                $config['password'],
                self::$options
            );
        }

        return self::$instance;
    }

    /**
     * @desc Execute a query and return the result.
     * @param string $query
     * @param boolean $return_error
     * @param array $parameters
     * @return array
     */
    public static function execQuery($query, $return_error = false, array $parameters = [])
    {
        //Prepare and execute the query
        $statement = self::getInstance()->prepare($query);
        $statement->execute($parameters);

        // Check for errors and return response
        $error = $statement->errorCode();
        if ($error != '00000') {
            Log::critical('mysql', $error);

            //var_dump($query);
            //var_dump($error);
            if ($return_error === false) {
                Error::throwError(400, 'unexpected_error');
            } else {
                return ['error' => $error];
            }
        }

        return (substr($query, 0, 6) == 'SELECT') ? $statement->fetchAll() : true;
    }

    /**
     * @desc Execute a transaction.
     * @param array $queries
     * @param boolean $return_error
     * @param array $parameters
     * @return array
     */
    public static function execTransaction($queries, $return_error = false, $parameters = [])
    {
        try {
            // Get instance
            $conn = self::getInstance();

            // Begin the transaction
            $conn->beginTransaction();

            if (empty($parameters)) {
                foreach ($queries as $key => $query) {
                    $conn->prepare($query)->execute();
                }
            } else {
                foreach ($queries as $key => $query) {
                    $conn->prepare($query)->execute([$parameters[$key]]);
                }
            }

            // Commit the transaction
            $conn->commit();
        } catch(PDOException $e) {
            // Roll back the transaction if something failed
            $conn->rollback();

            Log::critical('mysql', $e->getMessage());

            // Return error
            if ($return_error === false) {
                Error::throwError(400, 'unexpected_error');
            } else {
                return ['error' => $e->getMessage()];
            }
        }
    }

    /**
     * @desc Get the last inserted Id in the database.
     * @return string
     */
    public static function getLastInsertId()
    {
        return self::getInstance()->lastInsertId();
    }
}