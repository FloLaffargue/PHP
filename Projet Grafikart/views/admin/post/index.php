<?php

use App\Auth;
use App\Connection;
use App\Table\PostTable;

Auth::check();

$title = 'Administration';
$pdo = Connection::getPDO();

$postTable = new PostTable($pdo);

[$posts, $pagination] = $postTable->findPaginated();

$link = $router->url('admin_posts');

?>

<h1 class="text-center my-3">Administration</h1>

<div class="container-fluid">

    <?php if(isset($_GET['delete'])): ?>
        <div class="alert alert-success">
            La suppression a bien été effectuée  
        </div>
    <?php endif ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>
                    <a href="<?= $router->url('admin_post_new')?>" class="btn btn-secondary">Créer un article</a>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($posts as $post): ?>
                <tr>
                    <td><?= $post->getID() ?></td>
                    <td><?= esc($post->getName()) ?></td>
                    <td>
                        <a href="<?= $router->url('admin_post_edit', ['id' => $post->getID()])?>" class="btn btn-primary">
                            Modifier
                        </a>

                        <?php $url = $router->url('admin_post_delete', ['id' => $post->getID()]) ?>                        
                        <form action="<?= $url ?>" method="POST" onsubmit="return confirm('Voulez-vous supprimer cet article ?')" style="display:inline">
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

</div>


<div class="d-flex justify-content-between my-4">

    <?= $pagination->previousLink($link) ?>
    <?= $pagination->nextLink($link) ?>

</div>