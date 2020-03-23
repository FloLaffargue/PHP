<?php

require_once '../vendor/autoload.php';
require_once 'config.php';

use App\Post;

// require_once '../class/Post.php';

$error = null;
try {
    if (isset($_POST['name'], $_POST['content'])) {
        $query = $pdo->prepare('INSERT INTO posts (name, content, created_at) VALUES (:name, :content, :created)');
        $query->execute([
            'content' => $_POST['content'],
            'name' => $_POST['name'],
            'created' => time()
        ]);
        header('Location: blog/edit.php?id=' . $pdo->lastInsertId());
        exit();
    }
    $query = $pdo->query('SELECT * FROM POSTS');
    $posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);

} catch(PDOException $e) {
    $error =  $e->getMessage();
}

// echo '<pre>';
// print_r($posts);
// echo '</pre>';
?>

<?php if($error):?>
    <?= $error ?>
<?php else: ?>
<ul>
    <?php foreach($posts as $post): ?>
        <h2><a href="edit.php?id=<?=$post->id?>"><?= htmlentities($post->name)?></a></h2>
        <a href="clean.php?id=<?=$post->id?>">Supprimer l'article</a>
        <p><?= $post->getBody() ?></p>
        <p>Crée le: <?= $post->created_at->format('d/m/y')?></p>
    <?php endforeach ?>
</ul>
<?php endif ?>

<p>Ajouter un contenu:</p>

<form action="" method="POST"> 
    <label for="">Sujet</label>
    <input type="text" name="name">
    <label for="">Contenu</label>
    <textarea name="content" cols="30" rows="10"></textarea>
    <button type="submit">Valider</button>
</form>



