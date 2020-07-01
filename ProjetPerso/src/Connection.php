<?php

namespace App;

use PDO;

class Connection {

    private static $pdo;

    public static function getPDO(): PDO 
    {
        if(!isset(self::$pdo)) {
            self::$pdo = new PDO('mysql:dbname=flo;host=127.0.0.1:3306', 'root', 'root', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
        return self::$pdo;
    }
}