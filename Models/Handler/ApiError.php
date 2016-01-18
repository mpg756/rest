<?php

namespace Api\Models\Handler;


use Api\Handler\Json;
use Api\Handler\Logger;

class ApiError
{
    public function __construct(\Exception $e)
    {
        $this->show($e);
    }

    private function show(\Exception $e)
    {
        Logger::exception($e);
        return new Json(array('success' => false));
    }
}