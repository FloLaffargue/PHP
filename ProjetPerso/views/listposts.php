<?php

use App\Connection;
use App\Table\PostTable;
use App\Table\CategoryTable;

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo, $router);
$posts = $postTable->getPaginatedItems();
(new CategoryTable($pdo))->hydratePosts($posts);

?>

<h1>Liste des articles</h1>

<div class="container">

    <?php foreach($posts as $post): ?>

    <div class="card">
        <h1><?= $post->getName() ?></h1>

        <?php foreach($post->getCategories() as $category): ?>
            <a href="#"><?= $category->getName() ?></a>    
        <?php endforeach ?>

        <small><?= $post->getCreatedAt()->format('d-m-Y')?></small>
        <p><?= $post->getExcerpt() ?></p>

        <a href="<?= $router->url('postTemplate', ['slug' => $post->getSlug()])?>">Voir plus</a>

        <a href="<?= $router->url('deletePost', ['slug' => $post->getSlug()])?>">Supprimer le post</a>
    

    </div>

    <?php endforeach ?>

</div>

<?= $postTable->getPreviousLink() ?>
<?= $postTable->getNextLink() ?>


<style>
.card { width: 24%; border: 1px solid black}
.container {display: flex; flex-wrap: wrap;}
</style>

