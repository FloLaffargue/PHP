<?php

use App\HTML\Form;
use App\Connection;
use App\Model\User;
use App\Table\UserTable;
use App\Table\Exception\NotFoundException;

$user = new User();

$errors = [];

if (!empty($_POST)) {
    
    $user->setUsername($_POST['username']);
    $errors['password'] = 'Identifiant ou mot de passe incorrecte';
    
    if(!empty($_POST['username']) && !empty($_POST['password'])) {

        $pdo = Connection::getPDO();

        try {
            $u = (new UserTable($pdo))->findByUsername($_POST['username']);
            if(password_verify($_POST['password'],$u->getPassword())) {

                session_start();
                $_SESSION['auth'] = $u->getID();


                if(isset($_POST['uri'])) {
                    $url = 'Location: ' . $_POST['uri'];
                    header($url);
                    exit();
                }

                header('Location: ' . $router->url('admin_posts'));
                exit();
            }

        } catch(NotFoundException $e) {}
    } 

}

$form = new Form($user, $errors);

?>

<?php if(isset($_GET['forbidden'])): ?>
    <div class="alert alert-danger">
        Vous ne pouvez pas accéder à cette page sans être connecté
    </div>
<?php endif ?>

<h1>Se connecter</h1>

<form action="<?= $router->url('login') ?>" method="POST">

    <?= $form->input('username', "Nom d'utilisateur") ?>
    <?= $form->input('password', "Mot de passe") ?>
    <button type="submit" class="btn btn-primary">Se connecter</button>
    <input type="hidden" name="uri" value=<?= $_GET['uri'] ?? '' ?>>

</form>