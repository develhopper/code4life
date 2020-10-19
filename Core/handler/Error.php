<?php

namespace Core\handler;

class Error{
    private static $errors=[
        400=>"400 Bad Request",
        401=>"401 Unathorized",
        403=>"403 Forbidden",
        404=>"404 Not Found",
        405=>"405 Method Not Allowed",
    ];

    public static function send($code){
        http_response_code($code);
        echo self::$errors[$code];
        exit;
    }
}