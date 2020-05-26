<?php

use App\Auth;
use App\HTML\Form;
use App\Connection;
use App\Model\Post;
use App\ObjectData;
use App\Table\PostTable;
use App\Table\CategoryTable;
use App\Validators\PostValidator;
use App\Attachment\PostAttachment;

Auth::check();
$pdo = Connection::getPDO();

$errors = [];
$post = new Post();

$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();

if (!empty($_POST)) {
    
    $data = array_merge($_POST, $_FILES);
    $postTable = new PostTable($pdo);
    
    $v = new PostValidator($data, $postTable, null, array_keys($categories));

    if($v->validate()) {
        
        ObjectData::hydrate($post, $data, ['name', 'content', 'slug', 'image']);
        
        $pdo->beginTransaction();

        PostAttachment::upload($post);
        $postTable->createPost($post);
        $postTable->attachCategories($post->getID(),$_POST['categories_ids']);
        $pdo->commit();

        header('Location: ' . $router->url('admin_post_edit', ['id' => $post->getID()]) . '?created=true');
        exit();
        
    } else {
        $errors = $v->errors();
    }
}

$form = new Form($post, $errors);

?>

<h1>Créer un nouvel article</h1>

<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        L'article n'a pas pu être ajouté
    </div>
<?php endif ?>

<?php 
    $button = 'Enregistrer'; 
    require '_form.php' 
?>
