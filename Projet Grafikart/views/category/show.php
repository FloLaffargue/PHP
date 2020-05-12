<?php 

use App\URL;
use App\Connection;
use App\Model\Post;
use App\Model\Category;
use App\PaginatedQuery;
use App\Table\PostTable;
use App\Table\CategoryTable;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();

$category = (new CategoryTable($pdo))->find($id);

if($category->getSlug() != $slug) {

    $url = $router->url('category', ['slug' => $category->getSlug(), 'id' => $id]);

    http_response_code(301);
    header('Location: ' . $url);
}

$title = "Catégorie {$category->getName()}";

// Je recupère tous les posts liés à une catégorie, qui eux-mêmes sont liés à 0 ou n catégories
[$posts, $pagination] = (new PostTable($pdo))->findPaginatedForCategory($category->getID());

$link = $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]);

?>

<h1><?= esc($title)?></h1>

<div class="row">
    <?php foreach($posts as $post): ?>
    <div class="col-md-3">
        <?php require dirname(__DIR__) . '/post/card.php' ?>
    </div>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($link) ?>
    <?= $pagination->nextLink($link) ?>
</div>
