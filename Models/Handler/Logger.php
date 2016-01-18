<?php

namespace Api\Handler;


class Logger
{
    public static function exception(\Exception $e)
    {
        if(LOGGING){
            $massage = "Error occurred: " . $e->getMessage() . " | file: " . $e->getFile() . " | line: " . $e->getLine();
            new FileWriter($massage);
        }
    }
}