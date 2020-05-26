<?php

use App\HTML\Form;
use App\Connection;
use App\Model\Category;
use App\ObjectData;
use App\Table\CategoryTable;
use App\Validators\CategoryValidator;
use App\Auth;

Auth::check();

$errors = [];
$item = new Category();

if (!empty($_POST)) {
    
    $pdo = Connection::getPDO();
    $table = new CategoryTable($pdo);

    $v = new CategoryValidator($_POST, $table);

    if($v->validate()) {
        
        ObjectData::hydrate($item, $_POST, ['name', 'slug']);

        $table->create([
            'name' => $item->getName(),
            'slug' => $item->getSlug()
        ]);
        header('Location: ' . $router->url('admin_categories') . '?created=true');
        exit();
        
    } else {
        $errors = $v->errors();
    }
}

$form = new Form($item, $errors);

?>

<h1>Créer une catégorie</h1>

<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        La catégorie n'a pas pu être ajoutée
    </div>
<?php endif ?>

<?php 
    $button = 'Enregistrer'; 
    require '_form.php' 
?>
