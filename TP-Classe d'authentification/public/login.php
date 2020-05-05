<?php
require '../vendor/autoload.php';

use App\App;

session_start();

$auth = App::getAuth();
$error = false;
// $username = htmlentities($_POST['username'] ?? null);
// $password = htmlentities($_POST['password'] ?? null);

// if($auth->user() != null) {
//     header('Location: index.php');
//     exit();
// }

if(!empty($_POST)) {
    
    $user = $auth->login($_POST['username'], $_POST['password']);

    if($user) {
        header('Location: index.php?login=1?');
        exit();
    }
    $error = "Login ou mot de passe incorrect";

    
}
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Document</title>
</head>
<body>
    <h1>Page de connexion</h1>
    <div class="container">
        
        <form method="POST">
            <div class="form-group">
                <label for="">Login</label>
                <input type="text" name="username" class="form-control" placeholder="login"> 
            </div>
            <div class="form-group">
                <label for="">Mot de passe</label>
                <input type="text" name="password" class="form-control" placeholder="mot de passe">
            </div>
            <input type="submit" value="Valider" class="btn btn-primary">
        </form>
        
        <?php if($error): ?>
            <div class="alert alert-danger">
                <?= $error ?>
            </div>
        <?php endif ?>

        <?php if(isset($_GET['forbid'])): ?>
            <div class="alert alert-danger">
                Accès à la page interdit
            </div>
        <?php endif ?>
    </div>
</body>
</html>