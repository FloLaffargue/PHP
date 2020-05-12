<?php

require '../vendor/autoload.php';

define('DEBUG_TIME', microtime(true));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

if(isset($_GET['page']) && $_GET['page'] === '1') {

    $uri = explode('?', $_SERVER['REQUEST_URI'])[0]; 
    $get = $_GET;
    unset($get['page']);
    $param = http_build_query($get);
    if(!empty($param)) {
        $uri .= '?' . $param;
    }

    header('Location: ' . $uri);
    http_response_code(301);
    exit();
}

$router = new App\Router(dirname(__DIR__) . '/views');

// Attention à l'ordre des mappages de route, quand deux routes se ressemblent il faut toujours mettre la plus pointue avant la plus générique
$router
    ->get('/', 'post/index', 'home')
    ->get('/blog/category/[*:slug]-[i:id]', 'category/show', 'category')
    ->get('/blog/[*:slug]-[i:id]','post/show', 'post')
    ->run();


