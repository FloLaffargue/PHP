<?php

namespace App\Controller;

class HomeController {

    public function index($match, $router) {
        $GLOBALS['title'] = "Nous sommes sur la page d'accueil";
        require VIEW_PATH . 'home.php';
    } 
}