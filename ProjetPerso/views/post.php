<?php

use App\Connection;
use App\Table\PostTable;
use App\Table\CategoryTable;

$pdo = Connection::getPDO();

$slug = $match['params']['slug'];
$post = (new PostTable($pdo))->find($slug);
(new CategoryTable($pdo))->hydratePosts([$post]);

?>

<div class="container">

    <div style="max-width:300px;margin:auto">
        <h1><?= $post->getName() ?></h1>

        <?php foreach($post->getCategories() as $category): ?>
            <a href="#"><?= $category->getName() ?></a>    
        <?php endforeach ?>

        <small><?= $post->getCreatedAt()->format('d-m-Y')?></small>
        <p><?= $post->getContent() ?></p>
    </div>

</div>