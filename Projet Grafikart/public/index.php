<?php

require '../vendor/autoload.php';

define('DEBUG_TIME', microtime(true));
define('UPLOAD_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'uploads' );

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

    // Admin
    ->match('/login', 'auth/login', 'login')
    ->post('/logout', 'auth/logout', 'logout')

    // Front
    ->get('/', 'post/index', 'home')
    ->get('/blog/category/[*:slug]-[i:id]', 'category/show', 'category')
    ->get('/blog/[*:slug]-[i:id]','post/show', 'post')
    
    //Back
    // Gestion des articles
    ->match('/admin', 'admin/post/index', 'admin_posts')
    ->match('/admin/post/[i:id]', 'admin/post/edit', 'admin_post_edit')
    ->post('/admin/post/[i:id]/delete', 'admin/post/delete', 'admin_post_delete')
    ->match('/admin/post/new', 'admin/post/new', 'admin_post_new')  

    // Gestion des catérogies
    ->get('/admin/categories', 'admin/category/index', 'admin_categories')
    ->match('/admin/category/[i:id]', 'admin/category/edit', 'admin_category_edit')
    ->post('/admin/category/[i:id]/delete', 'admin/category/delete', 'admin_category_delete')
    ->match('/admin/category/new', 'admin/category/new', 'admin_category_new')  

    ->run();


