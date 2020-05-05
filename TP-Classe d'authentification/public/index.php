<?php
require '../vendor/autoload.php';

session_start();

use App\App;

$user = App::getAuth()->user();
$users = App::getPDO()->query('SELECT * FROM users')->fetchAll();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="p-4">
    <h1>Accèder aux pages</h1>

    <?php if(isset($_GET['login'])): ?> 
        <div class="alert alert-success">
            Vous êtes bien identifié
        </div>
    <?php endif ?> 

    <?php if($user): ?>
        Vous êtes connecté en tant que <?= $user->username ?> - <a href="logout.php">Se deconnecter</a>
    <?php endif ?>

    <ul>
        <li><a href="admin.php">Page réservée à l'administrateur</a></li>
        <li><a href="user.php">Page réservée à l'utilisateur</a></li>
    </ul>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pseudo</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['username'] ?></td>
                <td><?= $user['role'] ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>