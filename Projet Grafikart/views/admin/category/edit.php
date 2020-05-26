<?php

use App\Auth;
use App\HTML\Form;
use App\Connection;
use App\ObjectData;
use Valitron\Validator;
use App\Table\CategoryTable;
use App\Validators\CategoryValidator;

Auth::check();

$pdo = Connection::getPDO();
$id = $params['id'];

$table = new CategoryTable($pdo);
$item = $table->find($id);
$success = false;
$created = $_GET['created'] ?? false; 

$errors = [];

if (!empty($_POST)) {
    
    $v = new CategoryValidator($_POST, $table, $item->getID());

    if($v->validate()) {
        
        ObjectData::hydrate($item, $_POST, ['name', 'slug']);

        $table->update([
            'name' => $item->getName(),
            'slug' => $item->getSlug()
        ], $item->getID());
        $success = true;
        
    } else {
        $errors = $v->errors();
    }
}

$form = new Form($item, $errors);

?>

<h1>Editer la catégorie <?= esc($item->getName()) ?></h1>   

<?php if($success): ?>
    <div class="alert alert-success">
        la catégorie a bien été modifié
    </div>
<?php elseif($created != false): ?>
    <div class="alert alert-success">
        la catégorie a bien été ajouté
    </div>
<?php endif ?>

<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        la catégorie n'a pas pu être modifié
    </div>
<?php endif ?>

<?php 
    $button = 'Modifier'; 
    require '_form.php' 
?>
