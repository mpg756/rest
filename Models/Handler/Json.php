<?php

namespace Api\Handler;


class Json
{
    public function __construct($param)
    {
        if(is_array($param)) {
            $this->encode($param);
        }
        else {
            $this->decode($param);
        }
    }

    private function encode(array $param)
    {
        header('Content-Type:application/json');
        echo json_encode($param);
    }

    private function decode($string)
    {
        return json_decode($string);
    }
}