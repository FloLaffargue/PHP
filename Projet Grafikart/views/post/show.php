<?php

use App\Connection;
use App\Model\Post;
use App\Model\Category;
use App\Table\PostTable;
use App\Table\CategoryTable;

$id = (int)$params['id'];
$slug = $params['slug'];
$pdo = Connection::getPDO();


$post = (new PostTable($pdo))->find($id);

// Ce qu'il faut comprendre ici, c'est la méthode hydrate modifie l'aspect des objets contenus dans le tableau $post. En vérité, la variable $post contenu dans le scope global et la variable $post passée en paramétre à hydradePost() sont deux tableaux bien distincts, mais ayant comme élément des objets (ou des références à cet objet je pense). Cette méthode ne va pas dupliquer les objets du coup, mais modifier l'aspect de l'objet lui-même qui est partagé entre ces deux tableaux.

(new CategoryTable($pdo))->hydratePosts([$post]);

if($post->getSlug() != $slug) {
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
    http_response_code(301);
    header('Location: ' . $url);
}

?>

<h1 class="card-title"><?= esc($post->getName()) ?></h1>
<p class="text-muted"><?= $post->getCreatedAt()->format('d F Y ')?></p>

<?php foreach($post->getCategories() as $k => $category): ?>
    <?php $category_url = $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]) ?>
    <?= $k > 0 ? ',' : '' ?><a href="<?= $category_url ?>"><?= esc($category->getName())?></a>
<?php endforeach ?>

<?php if($post->getImage()): ?>
<p>
    <img src="<?= $post->getImageURL('large')?>" style="width:100%">
</p>
<?php endif ?>

<p><?= $post->getFormattedContent() ?></p>

