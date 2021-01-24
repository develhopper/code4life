<?php
namespace Core;

use Core\handler\Request;

class RegisterProvider{

    public static function register(Request $request){
        include 'providers.php';
        foreach ($providers as $provider) {
            call_user_func([$provider, "boot"],$request);
        }
    }
}