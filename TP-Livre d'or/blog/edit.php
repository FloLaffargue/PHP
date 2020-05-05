<?php
require_once 'config.php';
$error = null;
$success = null;
try {

    if (isset($_POST['name'],$_POST['content'])) {
        $query = $pdo->prepare('UPDATE posts SET name = :bite, content = :content WHERE id=:id');
        $query->execute([
            'bite' => $_POST['name'],
            'content' => $_POST['content'],
            'id' => $_POST['id']
        ]);
        $success = 'Votre message a bien été modifié';
    }

    // Requête préparée
    $query = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
    $query->execute([
        'id' => $_GET['id']
    ]);
    $post = $query->fetch();

} catch (PDOException $e) {
    $error = $e->getMessage();
}

?>

<?php if($error): ?>
    <?= $error ?>
<?php endif ?>

<?php if($success): ?>
    <?= $success ?>
<?php endif ?>
<a href="index.php">Revenir au listing</a>

<form action="" method="POST"> 
    <label for="">Sujet</label>
    <input type="text" value="<?= htmlentities($post->name)?>" name="name">
    <label for="">Contenu</label>
    <textarea name="content" cols="30" rows="10"><?= htmlentities($post->content)?></textarea>
    <input type="hidden" name="id" value="<?=$_GET['id']?>">
    <button type="submit">Valider</button>
</form>