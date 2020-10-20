<?php
namespace Core;

use Core\handler\Request;
class RegisterProvider{

    public static function register(){
        include 'providers.php';
        $request=new Request();
        foreach ($providers as $provider) {
            call_user_func([$provider, "boot"],$request);
        }
    }
}