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
        foreach($data as $key => $item){
            $values[] = $key;
            $values[] = $item;
        }
        $values[] = $id;

        try{
            $count = (count($values) - 1)/2 +1; //count pairs of key=>value + id
            if($count>1){
                $sql = 'UPDATE ADDRESS SET ';
                while($count>1){
                    $count--;
                    $sql.= ($count == 1) ? '? = ?' : '? = ?, ';
                }
                $sql.= ' WHERE ADDRESSID = ?';
                $this->_db->query($sql,$values);
            } else {
                $sql = 'UPDATE ADDRESS
                SET ? = ?
                WHERE ADDRESSID = ?';
                $this->_db->query($sql,$values);
            }
            return true;
        } catch(\Exception $e) {
            new ApiError($e);
        }
        return false;
    }
}