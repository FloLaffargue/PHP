<?php

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

class Autoloader {

    public static function autoload()
    {
        spl_autoload_register([Autoloader::class, 'register']);
    }

    public static function register($path) 
    {
        $path = str_replace('\\','/', $path);
        $path = str_replace('App','src', $path);

        require dirname(__DIR__) . '/' . $path . '.php';
    }

}