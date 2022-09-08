<?php
require __DIR__ . '/../vendor/autoload.php';

//Load .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

if (!function_exists('check_credentials')) 
{
    function check_credentials(){
        $headers = getallheaders();
        $result = false;
        if(isset($headers['APP_KEY']) && !empty($headers['APP_KEY'])){
            if(strcmp($_ENV['APP_KEY'], $headers['APP_KEY']) == 0)
                $result = true;
        }

        return $result;
    }
} 