<?php

use App\HTML\Form;
use App\Connection;
use App\ObjectData;
use Valitron\Validator;
use App\Table\PostTable;
use App\Table\CategoryTable;
use App\Validators\PostValidator;
use App\Attachment\PostAttachment;

$pdo = Connection::getPDO();
$id = $params['id'];

$postTable = new PostTable($pdo);
$categoryTable = new CategoryTable($pdo);

$post = $postTable->find($id);

// Permet d'hydrater l'article avec les catégories qui lui sont associées
$categoryTable->hydratePosts([$post]);

// Affiche toutes les catégories de la table, sans critère
$categories = $categoryTable->list();

$success = false;
$created = $_GET['created'] ?? false; 

$errors = [];

if (!empty($_POST)) {
    
    $data = array_merge($_POST, $_FILES);

    $v = new PostValidator($data, $postTable, $post->getID(), array_keys($categories));

    if($v->validate()) {
        
        ObjectData::hydrate($post, $data, ['name', 'content', 'slug','image']);
        $pdo->beginTransaction();

        PostAttachment::upload($post);
        $postTable->updatePost($post);
        $postTable->attachCategories($post->getID(), $_POST['categories_ids']);

        $pdo->commit();
        
        // J'hydrate de nouveau l'article avec les nouvelles catégories
        $categoryTable->hydratePosts([$post]);
        $success = true;

        
    } else {
        $errors = $v->errors();
    }
}

$form = new Form($post, $errors);

?>

<h1>Editer l'article <?= esc($post->getName()) ?></h1>   

<?php if($success): ?>
    <div class="alert alert-success">
        L'article a bien été modifié
    </div>
<?php elseif($created != false): ?>
    <div class="alert alert-success">
        L'article a bien été ajouté
    </div>
<?php endif ?>

<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        L'article n'a pas pu être modifié
    </div>
<?php endif ?>

<?php 
    $button = 'Modifier'; 
    require '_form.php' 
?>
