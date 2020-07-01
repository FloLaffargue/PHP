<?php
// Reste à faire
/*
    nettoyer les vues et mettre la logique dans les controllers
    bonus: faire le template de categorie, et un backoffice
*/
use App\DIC;
use App\Router;

define('VIEW_PATH', dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'views/');

require '../../vendor/autoload.php';
require '../Autoloader.php';
Autoloader::autoload();

$router = DIC::get('App\Router');

$router->map("/", "App\Controller\HomeController@index", "home");
$router->map("/home", "App\Controller\HomeController@index", "accueil");

$router->map("/post/[strnum:slug]", "App\Controller\PostController@show", "postTemplate");
$router->map("/posts", "App\Controller\PostController@index", "posts");
$router->map("/post/[strnum:slug]/delete", "App\Controller\PostController@delete", "deletePost");

$match = $router->match();

if($match == null) {
    throw new Exception('Cette route n\'existe pas');
} else {
    $controller = explode('@',$match['target']);
    $class = $controller[0];
    $method = $controller[1];
    ob_start();
    call_user_func_array([$class, $method], [$match, $router]);
    $content = ob_get_clean();
    require VIEW_PATH . 'layout.php';
}

