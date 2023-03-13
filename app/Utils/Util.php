<?php

namespace App\Utils;

use Carbon\Carbon;
use Exception;

class Util
{
    public static function convertMessageErrorFormRequest(array $messages)
    {
        $message = '';

        foreach ($messages as $key => $value) 
            $message .= $key.": ".$value[0].";";
        
        return $message;
    }

    public static function getStatusCode($code)
    {
        return $code > 1 ? ($code > 500 ? 500 : $code) : 404;
    }
}