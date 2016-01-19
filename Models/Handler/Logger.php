<?php

namespace Api\Handler;


class Logger
{
    public static function exception(\Exception $e)
    {
        if(LOGGING){
            $massage = date('Y-m-d H:i:s') . " Error occurred: " . $e->getMessage() . " | file: " . $e->getFile() . " | line: " . $e->getLine() . "\n";
            new FileWriter($massage);
        }
    }
}