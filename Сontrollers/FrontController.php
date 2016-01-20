<?php

namespace Api\Controllers;


use Api\Handler\Json;
use Api\Models\Database\Requests;

class FrontController
{
    private $_requestMethod;
    private $_requestParts;

    public function __construct()
    {
        $this->_requestMethod = $_SERVER['REQUEST_METHOD'];
        $request = $_SERVER['REQUEST_URI'];
        $this->_requestParts = explode('/', trim($request, '/'));
        if ($this->_requestParts[0] != 'addresses') {
            return $this->_returnFault('invalid request');
        }
        $this->getParam();
    }

    protected function getParam()
    {
        if ($this->_requestMethod == 'GET' && empty($this->_requestParts[1])) {
            $response = (new Requests())->getAllData();
            if(!$response){
                return $this->_returnFault('internal error: no data have found');
            }
            $response['success'] = true;
            return new Json($response);
        }
        elseif ($this->_requestMethod == 'POST' && empty($this->_requestParts[1])){
            $this->_validateInput();
        }
        elseif ((int)$this->_requestParts[1]) {
            if ($this->_requestMethod == 'PUT') {
                $this->_validateInput();
            } elseif ($this->_requestMethod == 'GET') {
                $id = abs((int)$this->_requestParts[1]);
                $response = (new Requests())->getSingleRow($id);
                return ($response) ? new Json($response) : $this->_returnFault('internal error');
            }
        } else {
            return $this->_returnFault('not int parameter is given');
        }
    }

    private function _returnFault($message = 'error')
    {
        return new Json(array('success' => false, 'message' => $message));
    }

    private function _checkInputType(array $input)
    {
        $inputTypes = (new Requests())->fields;
        foreach ($inputTypes as $key => $value){
            switch ($key){
                case 'int':
                    if(!is_int($input[$key])){
                        return false;
                    }
                    break;
                case 'string':
                    if(!is_string($input[$key])){
                        return false;
                    }
                    break;
            }
        }
        return true;
    }

    /**
     * Validations for input data
     * Works for create(POST) and edit(PUT)
    */
    private function _validateInput()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        if (!empty($input)) {
            $dbRequests = new Requests();
            $inputRows = array_change_key_case($input, CASE_UPPER ); // get only keys, convert them to upper case
            $avalRows = $dbRequests->fields;
            $values = array_intersect_key($inputRows,$avalRows); // check if input array contain valid fields
            if(!empty($values)){
                if(!$this->_checkInputType($values)) {
                    return $this->_returnFault('wrong parameter given');
                }
                if($this->_requestMethod == 'POST'){
                    if($dbRequests->addRow($values))
                    {
                        $response['success'] = true;
                        return new Json($response);
                    }
                }
                else {
                    if($dbRequests->setSingleRow($this->_requestParts[1],$values))
                    {
                        $response['success'] = true;
                        return new Json($response);
                    }
                }
            }
            else {
                return $this->_returnFault('wrong params given');
            }
        } else {
            if($this->_requestMethod == 'POST'){
                return $this->_returnFault('no parameters are given. nothing to create');
            }
            else {
                return $this->_returnFault('no parameters are given. nothing to update');
            }
        }
    }

}