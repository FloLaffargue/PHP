<?php

use App\Auth;
use App\Connection;
use App\Table\CategoryTable;

Auth::check();

$title = 'Gestion des catégories';
$pdo = Connection::getPDO();

$items = (new CategoryTable($pdo))->all();

$link = $router->url('admin_categories');

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
                <th>URL</th>
                <th>
                    <a href="<?= $router->url('admin_category_new')?>" class="btn btn-secondary">Nouveau</a>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item): ?>
                <tr>
                    <td><?= $item->getID() ?></td>
                    <td><?= esc($item->getName()) ?></td>
                    <td><?= $item->getSlug() ?></td>
                    <td>
                        <a href="<?= $router->url('admin_category_edit', ['id' => $item->getID()])?>" class="btn btn-primary">
                            Modifier
                        </a>

                        <?php $url = $router->url('admin_category_delete', ['id' => $item->getID()]) ?>                        
                        <form action="<?= $url ?>" method="POST" onsubmit="return confirm('Voulez-vous supprimer cette catégorie ?')" style="display:inline">
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

</div>
