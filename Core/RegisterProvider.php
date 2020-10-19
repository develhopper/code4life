<?php
namespace Core;
class RegisterProvider{

    public static function register(){
        include 'providers.php';
        foreach ($providers as $provider) {
            call_user_func([$provider, "boot"]);
        }
    }
}