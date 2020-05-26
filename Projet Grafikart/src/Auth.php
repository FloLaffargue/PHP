<?php 

namespace App;

use App\Security\ForbiddenException;

class Auth {

    public static function check() {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();    
        }
        if(!isset($_SESSION['auth'])) {
            throw new ForbiddenException($_SERVER['REQUEST_URI']);
        }
    } 

}