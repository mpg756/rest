<?php

namespace Api\Models\Database;


use Api\Models\Handler\ApiError;

class Requests
{
    private $_db;

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
                $sql .= ($_i == ($count - 1)) ? $keys[$_i] . ' = ?' : $keys[$_i] . ' = ?, '; //
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