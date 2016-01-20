<?php

namespace Api\Models\Database;


use Api\Models\Handler\ApiError;

class Requests
{
    private $_db;

    /**
     * Available fields with their types
     * */
    public $fields = array(
        'LABEL' => 'string',
        'STREET' => 'string',
        'HOUSENUMBER' => 'int',
        'POSTALCODE' => 'int',
        'CITY' => 'string',
        'COUNTRY' => 'string'
    );

    public function __construct()
    {
        $this->_db = Connection::getInstance();
    }

    /**
     * Get all data from database
     * @return array|null
    */
    public function getAllData()
    {
        $sql = 'SELECT ADDRESSID, LABEL, STREET, HOUSENUMBER, POSTALCODE, CITY, COUNTRY FROM ADDRESS';
        try {
            $result = $this->_db->query($sql);
            return $result;
        } catch(\PDOException $e) {
            new ApiError($e);
        }
        return null;
    }

    /**
     * Creates new row in table
     * @param array $data
     * @return bool
     */
    public function addRow(array $data)
    {
        $sql = 'INSERT INTO ADDRESS (LABEL, STREET, HOUSENUMBER, POSTALCODE, CITY, COUNTRY)
                VALUES (:label, :street, :housenumber, :postalcode, :city, :country)';
        $data = array_change_key_case($data, CASE_LOWER);
        try {
            $this->_db->query($sql,$data);
            return true;
        } catch(\PDOException $e) {
            new ApiError($e);
        }
        return false;
    }

    /**
     * Get requested row by $id
     * @param $id
     * @return array|null
     */
    public function getSingleRow($id)
    {
        $sql = 'SELECT LABEL, STREET, HOUSENUMBER, POSTALCODE, CITY, COUNTRY FROM ADDRESS WHERE ADDRESSID = ?';
        try {
            $result = $this->_db->query($sql,array($id));
            return $result;
        } catch(\PDOException $e) {
            new ApiError($e);
        }
        return null;
    }

    /**
     * Updates one row
     * sql request creates dynamically
     * @param int $id
     * @param array $data like ['street' => 'lomonosova']
     * @return bool
     */
    public function setSingleRow($id, array $data)
    {
        /*formatting array*/
        $values = [];
        $keys = [];
        foreach($data as $key => $item){
            $keys[] = $key;
            $values[] = $item;
        }
        $values[] = $id;

        try { // building string for pdo prepare statement
            $count = count($keys);
            $sql = 'UPDATE ADDRESS SET ';
            for ($_i = 0; $_i < $count; $_i++) {
                $sql .= ($_i == ($count - 1)) ? $keys[$_i] . ' = ?' : $keys[$_i] . ' = ?, ';
            }
            $sql .= ' WHERE ADDRESSID = ?';
            $this->_db->query($sql, $values);

            return true;
        } catch (\Exception $e) {
            new ApiError($e);
        }
        return false;
    }
}