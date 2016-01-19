<?php
namespace Api\Models\Database;

use Api\Models\Handler\ApiError;

class Connection
{
    private static $_instance = null;

    private $_db;

    private function __construct()
    {
        try {
            $this->_db = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        } catch (\PDOException $e) {
            new ApiError($e);
        }
    }

    private function __clone()
    {

    }

    private function __wakeup()
    {

    }

    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public function query($sql, array $params = []){
        try {
            $stmt = $this->_db->prepare($sql);
            $result = $stmt->execute($params);
            if(!$result) {
                throw new \PDOException('parameter has not been executed');
            }
            $all = $stmt->fetchAll(\PDO::FETCH_OBJ);
            return $all;
        } catch (\PDOException $e) {
            new ApiError($e);
        }
        return null;
    }
}